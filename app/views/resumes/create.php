<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="mx-auto max-w-5xl space-y-6">
    <div class="rounded-[2rem] border border-slate-200 bg-white/85 p-8 shadow-soft backdrop-blur-sm">
        <h3 class="text-3xl font-bold tracking-tight text-slate-950">Create Resume</h3>

        <?php if (!empty($errorMessage)): ?>
            <div class="mt-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700" role="alert">
                <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($infoMessage)): ?>
            <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="alert">
                <?= htmlspecialchars($infoMessage, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="rounded-[2rem] border border-slate-200 bg-white/85 p-8 shadow-soft backdrop-blur-sm">
        <h5 class="text-lg font-bold tracking-tight text-slate-950">Choose Resume Template</h5>
        <div class="mt-5 grid gap-4 md:grid-cols-3 xl:grid-cols-5">
            <?php foreach ($templates as $templateId => $template): ?>
                <label class="template-card group cursor-pointer rounded-[1.5rem] border p-5 text-center transition <?= ($selectedTemplate === $templateId) ? 'border-sky-400 bg-sky-50 shadow-lg shadow-sky-100' : 'border-slate-200 bg-slate-50/80 hover:border-sky-300 hover:bg-sky-50' ?>">
                    <input type="radio" name="template_id_radio" value="<?= $templateId ?>" <?= ($selectedTemplate === $templateId) ? 'checked' : '' ?> class="sr-only">
                    <div class="text-4xl"><?= htmlspecialchars($template['icon'], ENT_QUOTES, 'UTF-8') ?></div>
                    <h6 class="mt-3 text-sm font-semibold text-slate-950"><?= htmlspecialchars($template['name'], ENT_QUOTES, 'UTF-8') ?></h6>
                    <p class="mt-2 text-xs leading-6 text-slate-500"><?= htmlspecialchars($template['description'], ENT_QUOTES, 'UTF-8') ?></p>
                </label>
            <?php endforeach; ?>
        </div>
    </div>

    <form method="POST" action="<?= base_url('resume/store') ?>" class="rounded-[2rem] border border-slate-200 bg-white/85 p-8 shadow-soft backdrop-blur-sm">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="hidden" name="template_id" id="template_id" value="<?= htmlspecialchars($selectedTemplate, ENT_QUOTES, 'UTF-8') ?>">

        <div class="space-y-5">
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">LinkedIn Profile Text</label>
                <textarea name="linkedin_text" class="min-h-40 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-100" rows="6" placeholder="Paste your public LinkedIn profile text here, or copy the relevant profile sections from LinkedIn."><?= htmlspecialchars($formData['linkedin_text'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                <p class="mt-2 text-sm text-slate-500">If you paste LinkedIn profile text, the app will extract headline, experience, education, skills, and summary automatically. Manual fields below can override parsed values.</p>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Full Name</label>
                    <input type="text" name="full_name" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-100" value="<?= htmlspecialchars($formData['full_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Professional Headline</label>
                    <input type="text" name="headline" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-100" value="<?= htmlspecialchars($formData['headline'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="e.g. Full Stack Developer with 3+ years experience">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                    <input type="email" name="email" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-100" value="<?= htmlspecialchars($formData['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Phone</label>
                    <input type="text" name="phone" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-100" value="<?= htmlspecialchars($formData['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Address</label>
                    <input type="text" name="address" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-100" value="<?= htmlspecialchars($formData['address'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Professional Summary</label>
                    <textarea name="summary" class="min-h-32 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-100" rows="4" placeholder="Short profile summary"><?= htmlspecialchars($formData['summary'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Skills (comma separated)</label>
                    <input type="text" name="skills" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-100" value="<?= htmlspecialchars($formData['skills'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="PHP, MySQL, JavaScript">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Work Experience (one per line)</label>
                    <textarea name="experience_items" class="min-h-32 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-100" rows="4" placeholder="Software Engineer - ABC Corp (2023-2025)&#10;Intern - XYZ Ltd (2022)"><?= htmlspecialchars($formData['experience_items'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Education (one per line)</label>
                    <textarea name="education_items" class="min-h-32 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-100" rows="4" placeholder="BSc Computer Science - Tribhuvan University"><?= htmlspecialchars($formData['education_items'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Projects (one per line)</label>
                    <textarea name="projects" class="min-h-24 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-100" rows="3" placeholder="Job Portal Web App - PHP, MySQL"><?= htmlspecialchars($formData['projects'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Certifications (one per line)</label>
                    <textarea name="certifications" class="min-h-24 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-100" rows="3" placeholder="AWS Cloud Practitioner"><?= htmlspecialchars($formData['certifications'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
            </div>
        </div>

        <div class="mt-6 flex flex-wrap gap-3">
            <button type="button" onclick="submitWithAction('extract')" class="inline-flex rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">Extract from LinkedIn</button>
            <button type="submit" name="action" value="save" class="inline-flex rounded-full bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-500">Save Resume</button>
        </div>
    </form>

    <a href="<?= base_url('resume') ?>" class="inline-flex text-sm font-semibold text-sky-700 hover:text-sky-600">Back to resumes</a>
</section>

<script>
    function selectTemplate(templateId) {
        document.getElementById('template_id').value = templateId;

        document.querySelectorAll('.template-card').forEach(card => {
            card.classList.remove('border-sky-400', 'bg-sky-50', 'shadow-lg', 'shadow-sky-100');
            card.classList.add('border-slate-200', 'bg-slate-50/80');
        });

        const active = document.querySelector('.template-card input[value="' + templateId + '"]');
        if (active && active.closest('.template-card')) {
            const card = active.closest('.template-card');
            card.classList.add('border-sky-400', 'bg-sky-50', 'shadow-lg', 'shadow-sky-100');
            card.classList.remove('border-slate-200', 'bg-slate-50/80');
        }
    }

    document.addEventListener('submit', function(e) {
        if (!e.target.closest('form')) {
            return;
        }
    });

    function submitWithAction(actionValue) {
        const form = document.querySelector('form[action="<?= base_url('resume/store') ?>"]');
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'action';
        hiddenInput.value = actionValue;
        form.appendChild(hiddenInput);
        form.submit();
    }

    document.addEventListener('DOMContentLoaded', function() {
        selectTemplate(document.getElementById('template_id').value);
        document.querySelectorAll('.template-card').forEach(card => {
            card.addEventListener('click', function() {
                const radio = card.querySelector('input[type="radio"]');
                if (radio) {
                    selectTemplate(radio.value);
                }
            });
        });
    });
</script>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>