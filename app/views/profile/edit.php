<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<?php $profile = $profile ?? []; ?>
<?php $isEmployer = (($profile['role'] ?? '') === 'employer'); ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700" role="alert">
        <?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<section class="relative overflow-hidden rounded-[2rem] border border-slate-200 bg-white p-8 text-slate-900 shadow-soft lg:p-10">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(148,163,184,0.18),_transparent_30%),radial-gradient(circle_at_bottom_left,_rgba(226,232,240,0.7),_transparent_26%)]"></div>
    <div class="relative">
        <h3 class="text-3xl font-bold tracking-tight"><?= $isEmployer ? 'Edit Company Profile' : 'Edit Profile' ?></h3>
        <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-600 sm:text-base"><?= $isEmployer ? 'Set the company identity candidates will see on job posts and company pages.' : 'Add the profile details that make recommendations and public identity more useful.' ?></p>
    </div>
</section>

<div class="mt-6 rounded-[2rem] border border-slate-200 bg-white/90 p-8 shadow-soft backdrop-blur-sm">
    <form method="POST" action="<?= base_url('profile/update') ?>" enctype="multipart/form-data" class="space-y-6">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Name</label>
                <input type="text" name="name" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-slate-300 focus:bg-white focus:ring-4 focus:ring-slate-100" value="<?= htmlspecialchars((string)$profile['name'], ENT_QUOTES, 'UTF-8') ?>" required>
            </div>

            <?php if (!$isEmployer): ?>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Headline</label>
                    <input type="text" name="headline" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-slate-300 focus:bg-white focus:ring-4 focus:ring-slate-100" value="<?= htmlspecialchars((string)($profile['headline'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Location</label>
                    <input type="text" name="location" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-slate-300 focus:bg-white focus:ring-4 focus:ring-slate-100" value="<?= htmlspecialchars((string)($profile['location'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Website</label>
                    <input type="url" name="website" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-slate-300 focus:bg-white focus:ring-4 focus:ring-slate-100" value="<?= htmlspecialchars((string)($profile['website'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Bio</label>
                    <textarea name="bio" class="min-h-32 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-slate-300 focus:bg-white focus:ring-4 focus:ring-slate-100" rows="4"><?= htmlspecialchars((string)($profile['bio'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Profile Image</label>
                    <input type="file" name="profile_image" class="block w-full rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-700 file:mr-4 file:rounded-full file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-700" accept=".jpg,.jpeg,.png,.webp,.gif,image/*">
                    <?php if (!empty($profile['profile_image'])): ?>
                        <label class="mt-3 flex items-center gap-2 text-sm text-slate-600">
                            <input class="h-4 w-4 rounded border-slate-300 text-slate-600 focus:ring-slate-400" type="checkbox" name="remove_profile_image" value="1" id="remove_profile_image">
                            <span>Remove current image</span>
                        </label>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($isEmployer): ?>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Company Name</label>
                    <input type="text" name="company_name" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-slate-300 focus:bg-white focus:ring-4 focus:ring-slate-100" value="<?= htmlspecialchars((string)($profile['company_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Company Location</label>
                    <input type="text" name="company_location" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-slate-300 focus:bg-white focus:ring-4 focus:ring-slate-100" value="<?= htmlspecialchars((string)($profile['company_location'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Company Website</label>
                    <input type="url" name="company_website" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-slate-300 focus:bg-white focus:ring-4 focus:ring-slate-100" value="<?= htmlspecialchars((string)($profile['company_website'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Company Logo</label>
                    <input type="file" name="company_logo" class="block w-full rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-700 file:mr-4 file:rounded-full file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-700" accept=".jpg,.jpeg,.png,.webp,.gif,image/*">
                    <?php if (!empty($profile['company_logo'])): ?>
                        <label class="mt-3 flex items-center gap-2 text-sm text-slate-600">
                            <input class="h-4 w-4 rounded border-slate-300 text-slate-600 focus:ring-slate-400" type="checkbox" name="remove_company_logo" value="1" id="remove_company_logo">
                            <span>Remove current logo</span>
                        </label>
                    <?php endif; ?>
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Company Description</label>
                    <textarea name="company_description" class="min-h-32 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-slate-300 focus:bg-white focus:ring-4 focus:ring-slate-100" rows="5"><?= htmlspecialchars((string)($profile['company_description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
            <?php endif; ?>
        </div>

        <div class="flex flex-wrap gap-3">
            <button class="inline-flex rounded-full bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700">Save Changes</button>
            <a href="<?= base_url('profile') ?>" class="inline-flex rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">Cancel</a>
        </div>
    </form>
</div>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
