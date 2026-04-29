<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<h3>Create Resume</h3>

<?php if (!empty($errorMessage)): ?>
    <div class="alert alert-danger" role="alert">
        <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>
<?php if (!empty($infoMessage)): ?>
    <div class="alert alert-success" role="alert">
        <?= htmlspecialchars($infoMessage, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>

<!-- Template Selection -->
<div class="mb-4">
    <h5>Choose Resume Template</h5>
    <div class="row g-3">
        <?php foreach ($templates as $templateId => $template): ?>
            <div class="col-md-4">
                <div class="template-card card" style="cursor: pointer; transition: all 0.3s;"
                    onclick="selectTemplate('<?= $templateId ?>', event)">
                    <div class="card-body text-center">
                        <div style="font-size: 2.5em; margin-bottom: 10px;">
                            <?= htmlspecialchars($template['icon'], ENT_QUOTES, 'UTF-8') ?>
                        </div>
                        <h6 class="card-title"><?= htmlspecialchars($template['name'], ENT_QUOTES, 'UTF-8') ?></h6>
                        <p class="card-text small text-muted"><?= htmlspecialchars($template['description'], ENT_QUOTES, 'UTF-8') ?></p>
                        <input type="radio" name="template_id_radio" value="<?= $templateId ?>"
                            <?= ($selectedTemplate === $templateId) ? 'checked' : '' ?>
                            style="display: none;">
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
    .template-card {
        border: 2px solid #ddd;
        transition: all 0.3s;
    }

    .template-card:hover {
        border-color: #007bff;
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.15);
    }

    .template-card input:checked+.card-body,
    .template-card.active {
        border-color: #007bff;
        background-color: #f0f7ff;
    }

    .template-card.selected {
        border-color: #007bff;
        background-color: #f0f7ff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
    }
</style>

<form method="POST" action="<?= base_url('resume/store') ?>">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <input type="hidden" name="template_id" id="template_id" value="<?= htmlspecialchars($selectedTemplate, ENT_QUOTES, 'UTF-8') ?>">

    <div class="mb-3">
        <label>LinkedIn Profile Text</label>
        <textarea name="linkedin_text" class="form-control" rows="6" placeholder="Paste your public LinkedIn profile text here, or copy the relevant profile sections from LinkedIn."><?= htmlspecialchars($formData['linkedin_text'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        <small class="form-text text-muted">If you paste LinkedIn profile text, the app will extract headline, experience, education, skills, and summary automatically. Manual fields below can override parsed values.</small>
    </div>

    <div class="mb-3">
        <label>Full Name</label>
        <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($formData['full_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
    </div>

    <div class="mb-3">
        <label>Professional Headline</label>
        <input type="text" name="headline" class="form-control" value="<?= htmlspecialchars($formData['headline'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="e.g. Full Stack Developer with 3+ years experience">
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($formData['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>

    <div class="mb-3">
        <label>Phone</label>
        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($formData['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>

    <div class="mb-3">
        <label>Address</label>
        <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($formData['address'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>

    <div class="mb-3">
        <label>Professional Summary</label>
        <textarea name="summary" class="form-control" rows="4" placeholder="Short profile summary"><?= htmlspecialchars($formData['summary'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <div class="mb-3">
        <label>Skills (comma separated)</label>
        <input type="text" name="skills" class="form-control" value="<?= htmlspecialchars($formData['skills'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="PHP, MySQL, JavaScript">
    </div>

    <div class="mb-3">
        <label>Work Experience (one per line)</label>
        <textarea name="experience_items" class="form-control" rows="4" placeholder="Software Engineer - ABC Corp (2023-2025)&#10;Intern - XYZ Ltd (2022)"><?= htmlspecialchars($formData['experience_items'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <div class="mb-3">
        <label>Education (one per line)</label>
        <textarea name="education_items" class="form-control" rows="4" placeholder="BSc Computer Science - Tribhuvan University"><?= htmlspecialchars($formData['education_items'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <div class="mb-3">
        <label>Projects (one per line)</label>
        <textarea name="projects" class="form-control" rows="3" placeholder="Job Portal Web App - PHP, MySQL"><?= htmlspecialchars($formData['projects'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <div class="mb-3">
        <label>Certifications (one per line)</label>
        <textarea name="certifications" class="form-control" rows="3" placeholder="AWS Cloud Practitioner"><?= htmlspecialchars($formData['certifications'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <div class="d-flex gap-2">
        <button type="button" onclick="submitWithAction('extract')" class="btn btn-secondary">Extract from LinkedIn</button>
        <button type="submit" name="action" value="save" class="btn btn-primary" onclick="console.log('Save button clicked'); return true;">Save Resume</button>
    </div>


</form>

<a href="<?= base_url('resume') ?>" class="btn btn-link mt-2">Back to resumes</a>

<script>
    function selectTemplate(templateId, event) {
        document.getElementById('template_id').value = templateId;

        // Update visual selection
        document.querySelectorAll('.template-card').forEach(card => {
            card.classList.remove('selected');
        });

        if (event && event.target) {
            event.target.closest('.template-card').classList.add('selected');
        }
    }

    // Test form submission
    document.addEventListener('submit', function(e) {
        console.log('Form submitting with action:', e.target.action);
        console.log('Form method:', e.target.method);
        console.log('All form data:');
        for (let [key, value] of new FormData(e.target)) {
            console.log(key + ': ' + value);
        }
        console.log('Focused button:', document.activeElement?.name, document.activeElement?.value);
    });

    // Test button clicks
    document.querySelectorAll('[name="action"]').forEach(button => {
        button.addEventListener('click', function(e) {
            console.log('Button clicked:', e.target.value);
        });
    });

    // Debug form submission functions
    function submitWithAction(actionValue) {
        const form = document.querySelector('form');
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'action';
        hiddenInput.value = actionValue;
        form.appendChild(hiddenInput);
        console.log('Submitting with action:', actionValue);
        form.submit();
    }

    // Initialize visual state on page load
    document.addEventListener('DOMContentLoaded', function() {
        const selectedTemplate = document.getElementById('template_id').value;
        const cards = document.querySelectorAll('.template-card');
        cards.forEach((card, index) => {
            const radio = card.querySelector('input[type="radio"]');
            if (radio.checked) {
                card.classList.add('selected');
            }
        });
    });
</script>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>