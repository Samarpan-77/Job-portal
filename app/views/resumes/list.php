<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>
<?php require_once BASE_PATH . '/app/services/ResumeTemplateService.php'; ?>

<h3>My Resumes</h3>

<a href="<?= base_url('resume/create') ?>" class="btn btn-primary btn-sm mb-3">Create Resume</a>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Headline</th>
                <th>Template</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $templates = ResumeTemplateService::getAvailableTemplates();
            foreach ($resumes as $resume):
                $content = $resume['parsed_content'] ?? [];
                $templateId = $resume['template_id'] ?? 'classic';
                $template = $templates[$templateId] ?? $templates['classic'];
            ?>
                <tr>
                    <td><?= $resume['id'] ?></td>
                    <td><?= htmlspecialchars($content['full_name'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($content['headline'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <span title="<?= htmlspecialchars($template['description'], ENT_QUOTES, 'UTF-8') ?>">
                            <?= htmlspecialchars($template['icon'], ENT_QUOTES, 'UTF-8') ?>
                            <?= htmlspecialchars($template['name'], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($resume['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <a href="<?= base_url('resume/view/' . $resume['id']) ?>"
                            class="btn btn-outline-primary btn-sm">View</a>
                        <a href="<?= base_url('resume/delete/' . $resume['id']) ?>"
                            class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if (empty($resumes)): ?>
    <div class="alert alert-info">
        No resumes yet. <a href="<?= base_url('resume/create') ?>">Create your first resume</a>
    </div>
<?php endif; ?>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>