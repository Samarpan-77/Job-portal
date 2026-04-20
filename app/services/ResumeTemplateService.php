<?php

class ResumeTemplateService
{
    // Define available templates
    public static function getAvailableTemplates()
    {
        return [
            'classic' => [
                'id' => 'classic',
                'name' => 'Classic',
                'description' => 'Traditional, professional resume layout',
                'icon' => '📋'
            ],
            'modern' => [
                'id' => 'modern',
                'name' => 'Modern',
                'description' => 'Contemporary design with bold headings',
                'icon' => '✨'
            ],
            'minimal' => [
                'id' => 'minimal',
                'name' => 'Minimal',
                'description' => 'Clean and simple layout',
                'icon' => '⚪'
            ],
            'creative' => [
                'id' => 'creative',
                'name' => 'Creative',
                'description' => 'Colorful design for creative professionals',
                'icon' => '🎨'
            ],
            'technical' => [
                'id' => 'technical',
                'name' => 'Technical',
                'description' => 'Optimized for tech roles with skill emphasis',
                'icon' => '💻'
            ]
        ];
    }

    // Get a specific template
    public static function getTemplate($templateId)
    {
        $templates = self::getAvailableTemplates();
        return $templates[$templateId] ?? $templates['classic'];
    }

    // Render resume with selected template
    public static function renderResume($resumeData, $templateId = 'classic')
    {
        // Validate template
        $template = self::getTemplate($templateId);

        switch ($templateId) {
            case 'modern':
                return self::renderModernTemplate($resumeData);
            case 'minimal':
                return self::renderMinimalTemplate($resumeData);
            case 'creative':
                return self::renderCreativeTemplate($resumeData);
            case 'technical':
                return self::renderTechnicalTemplate($resumeData);
            case 'classic':
            default:
                return self::renderClassicTemplate($resumeData);
        }
    }

    // Classic Template - Traditional layout
    private static function renderClassicTemplate($data)
    {
        ob_start();
?>
        <div class="resume-classic">
            <style>
                .resume-classic {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 20px;
                    color: #333;
                    line-height: 1.6;
                }

                .resume-classic h2 {
                    border-bottom: 2px solid #333;
                    padding-bottom: 5px;
                    margin-top: 20px;
                    margin-bottom: 10px;
                    font-size: 1.3em;
                }

                .resume-classic h4 {
                    margin-bottom: 5px;
                    margin-top: 10px;
                }

                .resume-classic .section-item {
                    margin-bottom: 15px;
                }

                .resume-classic .item-header {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 3px;
                }

                .resume-classic .item-title {
                    font-weight: bold;
                }

                .resume-classic .item-subtitle {
                    font-style: italic;
                    color: #666;
                }

                .resume-classic .contact-info {
                    display: flex;
                    gap: 20px;
                    margin-bottom: 15px;
                    font-size: 0.9em;
                }

                .resume-classic .header {
                    text-align: center;
                    border-bottom: 2px solid #333;
                    padding-bottom: 15px;
                    margin-bottom: 15px;
                }

                .resume-classic .header h1 {
                    margin: 0;
                    font-size: 2em;
                }

                .resume-classic .header p {
                    margin: 5px 0;
                    color: #666;
                }
            </style>

            <div class="header">
                <h1><?= htmlspecialchars($data['full_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></h1>
                <?php if (!empty($data['headline'])): ?>
                    <p><?= htmlspecialchars($data['headline'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
            </div>

            <div class="contact-info">
                <?php if (!empty($data['email'])): ?>
                    <span>✉ <?= htmlspecialchars($data['email'], ENT_QUOTES, 'UTF-8') ?></span>
                <?php endif; ?>
                <?php if (!empty($data['phone'])): ?>
                    <span>📞 <?= htmlspecialchars($data['phone'], ENT_QUOTES, 'UTF-8') ?></span>
                <?php endif; ?>
                <?php if (!empty($data['address'])): ?>
                    <span>📍 <?= htmlspecialchars($data['address'], ENT_QUOTES, 'UTF-8') ?></span>
                <?php endif; ?>
            </div>

            <?php if (!empty($data['summary'])): ?>
                <div>
                    <h2>Professional Summary</h2>
                    <p><?= nl2br(htmlspecialchars($data['summary'], ENT_QUOTES, 'UTF-8')) ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($data['experience_items']) && is_array($data['experience_items'])): ?>
                <div>
                    <h2>Experience</h2>
                    <?php foreach ($data['experience_items'] as $exp): ?>
                        <div class="section-item">
                            <p><?= nl2br(htmlspecialchars($exp, ENT_QUOTES, 'UTF-8')) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($data['education_items']) && is_array($data['education_items'])): ?>
                <div>
                    <h2>Education</h2>
                    <?php foreach ($data['education_items'] as $edu): ?>
                        <div class="section-item">
                            <p><?= nl2br(htmlspecialchars($edu, ENT_QUOTES, 'UTF-8')) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($data['skills']) && is_array($data['skills'])): ?>
                <div>
                    <h2>Skills</h2>
                    <p><?= htmlspecialchars(implode(', ', $data['skills']), ENT_QUOTES, 'UTF-8') ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($data['certifications']) && is_array($data['certifications'])): ?>
                <div>
                    <h2>Certifications</h2>
                    <ul>
                        <?php foreach ($data['certifications'] as $cert): ?>
                            <li><?= htmlspecialchars($cert, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($data['projects']) && is_array($data['projects'])): ?>
                <div>
                    <h2>Projects</h2>
                    <?php foreach ($data['projects'] as $project): ?>
                        <div class="section-item">
                            <p><?= nl2br(htmlspecialchars($project, ENT_QUOTES, 'UTF-8')) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php
        return ob_get_clean();
    }

    // Modern Template
    private static function renderModernTemplate($data)
    {
        ob_start();
    ?>
        <div class="resume-modern">
            <style>
                .resume-modern {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 20px;
                    color: #333;
                    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                }

                .resume-modern .header {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    padding: 30px;
                    border-radius: 8px;
                    margin-bottom: 20px;
                    text-align: center;
                }

                .resume-modern h1 {
                    margin: 0;
                    font-size: 2.2em;
                    font-weight: 700;
                }

                .resume-modern .headline {
                    font-size: 1.1em;
                    margin-top: 10px;
                    opacity: 0.9;
                }

                .resume-modern .contact-info {
                    display: flex;
                    gap: 20px;
                    margin-top: 15px;
                    justify-content: center;
                    font-size: 0.9em;
                }

                .resume-modern h2 {
                    background: #667eea;
                    color: white;
                    padding: 10px 15px;
                    margin: 25px 0 15px 0;
                    border-radius: 4px;
                    font-size: 1.2em;
                }

                .resume-modern .section-item {
                    margin-bottom: 15px;
                    padding: 10px;
                    background: white;
                    border-radius: 4px;
                    border-left: 4px solid #667eea;
                }

                .resume-modern .item-header {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 5px;
                }

                .resume-modern .item-title {
                    font-weight: 700;
                    font-size: 1.05em;
                }

                .resume-modern .item-subtitle {
                    color: #667eea;
                    font-weight: 600;
                }

                .resume-modern .skills-list {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 8px;
                }

                .resume-modern .skill-badge {
                    background: #667eea;
                    color: white;
                    padding: 5px 10px;
                    border-radius: 20px;
                    font-size: 0.85em;
                }
            </style>

            <div class="header">
                <h1><?= htmlspecialchars($data['full_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></h1>
                <?php if (!empty($data['headline'])): ?>
                    <p class="headline"><?= htmlspecialchars($data['headline'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
                <div class="contact-info">
                    <?php if (!empty($data['email'])): ?>
                        <span>✉ <?= htmlspecialchars($data['email'], ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                    <?php if (!empty($data['phone'])): ?>
                        <span>📞 <?= htmlspecialchars($data['phone'], ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                    <?php if (!empty($data['address'])): ?>
                        <span>📍 <?= htmlspecialchars($data['address'], ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($data['summary'])): ?>
                <h2>About</h2>
                <p><?= nl2br(htmlspecialchars($data['summary'], ENT_QUOTES, 'UTF-8')) ?></p>
            <?php endif; ?>

            <?php if (!empty($data['experience_items']) && is_array($data['experience_items'])): ?>
                <h2>Experience</h2>
                <?php foreach ($data['experience_items'] as $exp): ?>
                    <div class="section-item">
                        <p><?= nl2br(htmlspecialchars($exp, ENT_QUOTES, 'UTF-8')) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($data['education_items']) && is_array($data['education_items'])): ?>
                <h2>Education</h2>
                <?php foreach ($data['education_items'] as $edu): ?>
                    <div class="section-item">
                        <p><?= nl2br(htmlspecialchars($edu, ENT_QUOTES, 'UTF-8')) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($data['skills']) && is_array($data['skills'])): ?>
                <h2>Skills</h2>
                <div class="skills-list">
                    <?php foreach ($data['skills'] as $skill): ?>
                        <span class="skill-badge"><?= htmlspecialchars($skill, ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($data['certifications']) && is_array($data['certifications'])): ?>
                <h2>Certifications</h2>
                <ul>
                    <?php foreach ($data['certifications'] as $cert): ?>
                        <li><?= htmlspecialchars($cert, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if (!empty($data['projects']) && is_array($data['projects'])): ?>
                <h2>Projects</h2>
                <?php foreach ($data['projects'] as $project): ?>
                    <div class="section-item">
                        <p><?= nl2br(htmlspecialchars($project, ENT_QUOTES, 'UTF-8')) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php
        return ob_get_clean();
    }

    // Minimal Template
    private static function renderMinimalTemplate($data)
    {
        ob_start();
    ?>
        <div class="resume-minimal">
            <style>
                .resume-minimal {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 20px;
                    color: #333;
                }

                .resume-minimal h1 {
                    font-size: 1.8em;
                    margin: 0;
                    font-weight: 700;
                }

                .resume-minimal .header {
                    margin-bottom: 20px;
                }

                .resume-minimal .contact-info {
                    display: flex;
                    gap: 15px;
                    margin: 10px 0;
                    font-size: 0.85em;
                    color: #666;
                }

                .resume-minimal h2 {
                    font-size: 1em;
                    font-weight: 700;
                    margin: 15px 0 8px 0;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                    border-bottom: 1px solid #ccc;
                    padding-bottom: 5px;
                }

                .resume-minimal .section-item {
                    margin-bottom: 10px;
                }

                .resume-minimal .item-header {
                    display: flex;
                    justify-content: space-between;
                    font-weight: 600;
                }

                .resume-minimal .item-subtitle {
                    color: #666;
                    font-size: 0.9em;
                }

                .resume-minimal p {
                    margin: 5px 0;
                    font-size: 0.95em;
                }

                .resume-minimal .skills-list {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 10px;
                }
            </style>

            <div class="header">
                <h1><?= htmlspecialchars($data['full_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></h1>
                <?php if (!empty($data['headline'])): ?>
                    <p style="margin: 5px 0; color: #666;"><?= htmlspecialchars($data['headline'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
                <div class="contact-info">
                    <?php if (!empty($data['email'])): ?>
                        <span><?= htmlspecialchars($data['email'], ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                    <?php if (!empty($data['phone'])): ?>
                        <span><?= htmlspecialchars($data['phone'], ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                    <?php if (!empty($data['address'])): ?>
                        <span><?= htmlspecialchars($data['address'], ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($data['summary'])): ?>
                <h2>Summary</h2>
                <p><?= nl2br(htmlspecialchars($data['summary'], ENT_QUOTES, 'UTF-8')) ?></p>
            <?php endif; ?>

            <?php if (!empty($data['experience_items']) && is_array($data['experience_items'])): ?>
                <h2>Experience</h2>
                <?php foreach ($data['experience_items'] as $exp): ?>
                    <div class="section-item">
                        <p><?= nl2br(htmlspecialchars($exp, ENT_QUOTES, 'UTF-8')) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($data['education_items']) && is_array($data['education_items'])): ?>
                <h2>Education</h2>
                <?php foreach ($data['education_items'] as $edu): ?>
                    <div class="section-item">
                        <p><?= nl2br(htmlspecialchars($edu, ENT_QUOTES, 'UTF-8')) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($data['skills']) && is_array($data['skills'])): ?>
                <h2>Skills</h2>
                <div class="skills-list">
                    <?php foreach ($data['skills'] as $skill): ?>
                        <span><?= htmlspecialchars($skill, ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($data['certifications']) && is_array($data['certifications'])): ?>
                <h2>Certifications</h2>
                <ul style="margin: 5px 0; padding-left: 20px;">
                    <?php foreach ($data['certifications'] as $cert): ?>
                        <li><?= htmlspecialchars($cert, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if (!empty($data['projects']) && is_array($data['projects'])): ?>
                <h2>Projects</h2>
                <?php foreach ($data['projects'] as $project): ?>
                    <div class="section-item">
                        <p><?= nl2br(htmlspecialchars($project, ENT_QUOTES, 'UTF-8')) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php
        return ob_get_clean();
    }

    // Creative Template
    private static function renderCreativeTemplate($data)
    {
        ob_start();
    ?>
        <div class="resume-creative">
            <style>
                .resume-creative {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 20px;
                    color: #333;
                }

                .resume-creative .header {
                    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                    color: white;
                    padding: 40px;
                    border-radius: 10px;
                    margin-bottom: 20px;
                    text-align: center;
                }

                .resume-creative h1 {
                    margin: 0;
                    font-size: 2.5em;
                    font-weight: 700;
                }

                .resume-creative .headline {
                    font-size: 1.2em;
                    margin-top: 10px;
                    opacity: 0.95;
                }

                .resume-creative .contact-info {
                    display: flex;
                    gap: 20px;
                    margin-top: 15px;
                    justify-content: center;
                    font-size: 0.9em;
                }

                .resume-creative h2 {
                    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                    color: white;
                    padding: 12px 20px;
                    margin: 25px 0 15px 0;
                    border-radius: 5px;
                    font-size: 1.3em;
                }

                .resume-creative .section-item {
                    margin-bottom: 15px;
                    padding: 12px;
                    border-left: 4px solid #f5576c;
                    background: #f9f9f9;
                    border-radius: 4px;
                }

                .resume-creative .item-header {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 5px;
                }

                .resume-creative .item-title {
                    font-weight: 700;
                    font-size: 1.1em;
                    color: #f5576c;
                }

                .resume-creative .item-subtitle {
                    color: #666;
                    font-weight: 600;
                }

                .resume-creative .skills-list {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 10px;
                }

                .resume-creative .skill-badge {
                    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                    color: white;
                    padding: 6px 12px;
                    border-radius: 20px;
                    font-size: 0.85em;
                }
            </style>

            <div class="header">
                <h1><?= htmlspecialchars($data['full_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></h1>
                <?php if (!empty($data['headline'])): ?>
                    <p class="headline"><?= htmlspecialchars($data['headline'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
                <div class="contact-info">
                    <?php if (!empty($data['email'])): ?>
                        <span>✉ <?= htmlspecialchars($data['email'], ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                    <?php if (!empty($data['phone'])): ?>
                        <span>📞 <?= htmlspecialchars($data['phone'], ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                    <?php if (!empty($data['address'])): ?>
                        <span>📍 <?= htmlspecialchars($data['address'], ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($data['summary'])): ?>
                <h2>About Me</h2>
                <p><?= nl2br(htmlspecialchars($data['summary'], ENT_QUOTES, 'UTF-8')) ?></p>
            <?php endif; ?>

            <?php if (!empty($data['experience_items']) && is_array($data['experience_items'])): ?>
                <h2>Experience</h2>
                <?php foreach ($data['experience_items'] as $exp): ?>
                    <div class="section-item">
                        <p><?= nl2br(htmlspecialchars($exp, ENT_QUOTES, 'UTF-8')) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($data['education_items']) && is_array($data['education_items'])): ?>
                <h2>Education</h2>
                <?php foreach ($data['education_items'] as $edu): ?>
                    <div class="section-item">
                        <p><?= nl2br(htmlspecialchars($edu, ENT_QUOTES, 'UTF-8')) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($data['skills']) && is_array($data['skills'])): ?>
                <h2>Skills</h2>
                <div class="skills-list">
                    <?php foreach ($data['skills'] as $skill): ?>
                        <span class="skill-badge"><?= htmlspecialchars($skill, ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($data['certifications']) && is_array($data['certifications'])): ?>
                <h2>Certifications</h2>
                <ul>
                    <?php foreach ($data['certifications'] as $cert): ?>
                        <li><?= htmlspecialchars($cert, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if (!empty($data['projects']) && is_array($data['projects'])): ?>
                <h2>Projects</h2>
                <?php foreach ($data['projects'] as $project): ?>
                    <div class="section-item">
                        <p style="color: #f5576c; margin: 0; font-weight: 600;"><?= nl2br(htmlspecialchars($project, ENT_QUOTES, 'UTF-8')) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php
        return ob_get_clean();
    }

    // Technical Template
    private static function renderTechnicalTemplate($data)
    {
        ob_start();
    ?>
        <div class="resume-technical">
            <style>
                .resume-technical {
                    font-family: 'Courier New', monospace;
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 20px;
                    color: #333;
                    background: #f5f5f5;
                }

                .resume-technical .header {
                    background: #2d3748;
                    color: #fff;
                    padding: 25px;
                    margin-bottom: 20px;
                    border-radius: 4px;
                }

                .resume-technical h1 {
                    margin: 0;
                    font-size: 2em;
                    color: #4fc3f7;
                }

                .resume-technical .headline {
                    color: #bbb;
                    margin-top: 5px;
                    font-style: italic;
                }

                .resume-technical .contact-info {
                    display: flex;
                    gap: 15px;
                    margin-top: 10px;
                    font-size: 0.85em;
                    color: #aaa;
                }

                .resume-technical h2 {
                    color: #2d3748;
                    border-left: 4px solid #4fc3f7;
                    padding-left: 10px;
                    margin: 20px 0 10px 0;
                    font-size: 1.1em;
                }

                .resume-technical .section-item {
                    margin-bottom: 12px;
                    padding: 10px;
                    background: white;
                    border-left: 3px solid #4fc3f7;
                }

                .resume-technical .item-header {
                    display: flex;
                    justify-content: space-between;
                    font-weight: bold;
                    color: #2d3748;
                }

                .resume-technical .item-subtitle {
                    color: #4fc3f7;
                    font-weight: bold;
                    font-size: 0.9em;
                }

                .resume-technical .skills-list {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
                    gap: 8px;
                }

                .resume-technical .skill-badge {
                    background: #2d3748;
                    color: #4fc3f7;
                    padding: 5px 10px;
                    border-radius: 3px;
                    font-size: 0.8em;
                    text-align: center;
                }

                .resume-technical p {
                    margin: 5px 0;
                    font-size: 0.9em;
                }
            </style>

            <div class="header">
                <h1>$ <?= htmlspecialchars($data['full_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></h1>
                <?php if (!empty($data['headline'])): ?>
                    <p class="headline">> <?= htmlspecialchars($data['headline'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
                <div class="contact-info">
                    <?php if (!empty($data['email'])): ?>
                        <span>email: <?= htmlspecialchars($data['email'], ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                    <?php if (!empty($data['phone'])): ?>
                        <span>phone: <?= htmlspecialchars($data['phone'], ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                    <?php if (!empty($data['address'])): ?>
                        <span>location: <?= htmlspecialchars($data['address'], ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($data['summary'])): ?>
                <h2>$ summary</h2>
                <p><?= nl2br(htmlspecialchars($data['summary'], ENT_QUOTES, 'UTF-8')) ?></p>
            <?php endif; ?>

            <?php if (!empty($data['skills']) && is_array($data['skills'])): ?>
                <h2>$ skills</h2>
                <div class="skills-list">
                    <?php foreach ($data['skills'] as $skill): ?>
                        <div class="skill-badge"><?= htmlspecialchars($skill, ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($data['experience_items']) && is_array($data['experience_items'])): ?>
                <h2>$ experience</h2>
                <?php foreach ($data['experience_items'] as $exp): ?>
                    <div class="section-item">
                        <p><?= nl2br(htmlspecialchars($exp, ENT_QUOTES, 'UTF-8')) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($data['education_items']) && is_array($data['education_items'])): ?>
                <h2>$ education</h2>
                <?php foreach ($data['education_items'] as $edu): ?>
                    <div class="section-item">
                        <p><?= nl2br(htmlspecialchars($edu, ENT_QUOTES, 'UTF-8')) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($data['certifications']) && is_array($data['certifications'])): ?>
                <h2>$ certifications</h2>
                <ul style="margin: 5px 0; padding-left: 20px;">
                    <?php foreach ($data['certifications'] as $cert): ?>
                        <li><?= htmlspecialchars($cert, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if (!empty($data['projects']) && is_array($data['projects'])): ?>
                <h2>$ projects</h2>
                <?php foreach ($data['projects'] as $project): ?>
                    <div class="section-item">
                        <p style="font-weight: bold; color: #4fc3f7;"><?= nl2br(htmlspecialchars($project, ENT_QUOTES, 'UTF-8')) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
<?php
        return ob_get_clean();
    }
}
