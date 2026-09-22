<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card shadow-sm p-4">
            <h2 class="mb-3 text-center">Upload a Photo</h2>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $err): ?>
                            <li><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= url('/photo/store') ?>" method="POST" enctype="multipart/form-data" novalidate id="uploadForm">
                <div class="mb-3">
                    <label class="form-label" for="photo">Image File</label>
                    <input type="file" class="form-control" id="photo" name="photo" accept="image/jpeg,image/png,image/gif,image/webp" required>
                    <div class="invalid-feedback">Please choose a JPG, PNG, GIF, or WEBP image.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" maxlength="200" required>
                    <div class="invalid-feedback">Title is required (max 200 characters).</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="description">Description <span class="text-muted small">(optional)</span></label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>

                <?php if (!empty($taggableUsers)): ?>
                <div class="mb-3">
                    <label class="form-label">Tag People <span class="text-muted small">(optional)</span></label>
                    <div class="border rounded p-2" style="max-height:160px;overflow-y:auto;">
                        <?php foreach ($taggableUsers as $u): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="tags[]"
                                       value="<?= (int) $u['id'] ?>" id="tag_<?= (int) $u['id'] ?>">
                                <label class="form-check-label" for="tag_<?= (int) $u['id'] ?>">
                                    <?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name'], ENT_QUOTES, 'UTF-8') ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="form-text">Tagged people will be shown on the photo's detail page.</div>
                </div>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary w-100">Upload</button>
            </form>
        </div>
    </div>
</div>
