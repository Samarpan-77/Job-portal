<?php

// Auto-load libraries if available (composer or manual installation)
$vendorBasePath = dirname(__DIR__, 2) . '/vendor';
$vendorAutoload = $vendorBasePath . '/autoload.php';
if (file_exists($vendorAutoload)) {
    require_once $vendorAutoload;
}

$tcpdfPaths = [
    $vendorBasePath . '/tcpdf/tcpdf.php',
    $vendorBasePath . '/tecnickcom/tcpdf/tcpdf.php',
];
foreach ($tcpdfPaths as $path) {
    if (!class_exists('TCPDF') && file_exists($path)) {
        require_once $path;
        break;
    }
}

$html2pdfDir = $vendorBasePath . '/html2pdf';
if (is_dir($html2pdfDir)) {
    spl_autoload_register(function ($class) use ($html2pdfDir) {
        $prefix = 'Spipu\\Html2Pdf\\';
        if (strpos($class, $prefix) !== 0) {
            return;
        }

        $relativeClass = substr($class, strlen($prefix));
        $file = $html2pdfDir . '/' . str_replace('\\', '/', $relativeClass) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    });

    $html2pdfMain = $html2pdfDir . '/Html2Pdf.php';
    if (file_exists($html2pdfMain) && class_exists('TCPDF')) {
        require_once $html2pdfMain;
    }
}

/**
 * PDF Service for generating and downloading resume PDFs
 * Using html2pdf library
 */
class PDFService
{
    /**
     * Generate PDF from HTML content
     * @param string $html HTML content to convert
     * @param string $filename Output filename
     * @param string $outputPath Path where to save (null for download)
     * @return bool|string True if saved to file, false if error, or HTML if preview
     */
    public static function generatePDF($html, $filename = 'resume.pdf', $outputPath = null)
    {
        try {
            // Remove any output buffering
            if (ob_get_level()) {
                ob_end_clean();
            }

            // Clean HTML content for PDF
            $cleanHtml = self::cleanHtmlForPDF($html);

            // Prefer Html2Pdf only when TCPDF is available too, since Html2Pdf depends on TCPDF.
            if ((class_exists('Html2Pdf', false) || class_exists('Spipu\\Html2Pdf\\Html2Pdf', false)) && class_exists('TCPDF')) {
                return self::generateWithHtml2Pdf($cleanHtml, $filename, $outputPath);
            }
            // Fallback to TCPDF if available
            elseif (class_exists('TCPDF')) {
                return self::generateWithTCPDF($cleanHtml, $filename, $outputPath);
            }
            // Fallback to simple browser print
            else {
                return self::generateWithBrowserPrint($cleanHtml, $filename);
            }
        } catch (\Throwable $e) {
            error_log("PDF Generation Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate PDF using Html2Pdf library
     */
    private static function generateWithHtml2Pdf($html, $filename, $outputPath)
    {
        try {
            if (class_exists('Spipu\\Html2Pdf\\Html2Pdf', false)) {
                $className = 'Spipu\\Html2Pdf\\Html2Pdf';
            } elseif (class_exists('Html2Pdf', false)) {
                $className = 'Html2Pdf';
            } else {
                throw new Exception('Html2Pdf library not available.');
            }

            $pdf = new $className('P', 'A4', 'en');
            $pdf->writeHTML($html);

            if ($outputPath) {
                // Save to file
                $pdf->output($outputPath, 'F');
                return true;
            } else {
                // Download to browser
                $pdf->output($filename);
                exit;
            }
        } catch (\Throwable $e) {
            error_log("Html2Pdf Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate PDF using TCPDF library
     */
    private static function generateWithTCPDF($html, $filename, $outputPath)
    {
        try {
            $className = 'TCPDF';
            if (!class_exists($className)) {
                throw new Exception('TCPDF library not available.');
            }

            $pdf = new $className('P', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->SetMargins(10, 10, 10);
            $pdf->AddPage();
            $pdf->SetFont('helvetica', '', 12);
            $pdf->writeHTML($html, true, false, true, false, '');

            if ($outputPath) {
                // Save to file
                $pdf->Output($outputPath, 'F');
                return true;
            } else {
                // Download to browser
                $pdf->Output($filename, 'D');
                exit;
            }
        } catch (\Throwable $e) {
            error_log("TCPDF Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Fallback: Generate printable HTML for browser
     */
    private static function generateWithBrowserPrint($html, $filename)
    {
        // Use HTML content type (not PDF, since we're sending HTML)
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html>' .
            '<html>' .
            '<head>' .
            '<meta charset="utf-8">' .
            '<meta name="viewport" content="width=device-width, initial-scale=1.0">' .
            '<title>' . htmlspecialchars($filename, ENT_QUOTES, 'UTF-8') . '</title>' .
            '<style>' .
            '* { margin: 0; padding: 0; box-sizing: border-box; }' .
            'html, body { width: 100%; height: 100%; }' .
            'body { font-family: "Segoe UI", Arial, sans-serif; color: #333; line-height: 1.5; padding: 20px; }' .
            '@page { size: A4; margin: 15mm; }' .
            '@media print { body { margin: 0; padding: 15mm; width: 100%; } .no-print { display: none !important; } }' .
            '</style>' .
            '</head>' .
            '<body>' .
            $html .
            '<script>' .
            'window.onload = function() { setTimeout(function() { window.print(); }, 500); };' .
            '</script>' .
            '</body>' .
            '</html>';
        exit;
    }

    /**
     * Clean HTML for PDF generation
     */
    private static function cleanHtmlForPDF($html)
    {
        // Remove print-only styles section
        $html = preg_replace('/@media\s+print\s*\{[^}]*\}/i', '', $html);

        // Remove navigation and button elements
        $html = preg_replace('/<nav[^>]*>.*?<\/nav>/is', '', $html);
        $html = preg_replace('/<div[^>]*class="[^"]*template-selector[^"]*"[^>]*>.*?<\/div>/is', '', $html);
        $html = preg_replace('/<div[^>]*class="[^"]*resume-actions[^"]*"[^>]*>.*?<\/div>/is', '', $html);
        $html = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $html);

        // Clean up excessive whitespace
        $html = preg_replace('/\s+/', ' ', $html);
        $html = preg_replace('/>\s+</', '><', $html);

        return trim($html);
    }

    /**
     * Download file helper
     */
    public static function downloadFile($filePath, $filename)
    {
        if (!file_exists($filePath)) {
            return false;
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        header('Content-Length: ' . filesize($filePath));
        header('Pragma: public');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

        readfile($filePath);
        return true;
    }
}
