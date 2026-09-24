
<div class="row g-4">
    <div class="col-lg-7">
        <div class="d-flex align-items-start gap-2">
            <a href="<?= url('/photos') ?>" class="btn btn-primary btn-sm flex-shrink-0" title="Back to Gallery">&larr;</a>
            <img src="<?= url('/images/uploads/' . htmlspecialchars($photo['file_name'], ENT_QUOTES, 'UTF-8')) ?>"
                 class="img-fluid rounded shadow-sm w-100" style="max-height:600px;object-fit:contain;background:#111;"
                 alt="<?= htmlspecialchars($photo['title'], ENT_QUOTES, 'UTF-8') ?>">
        </div>
    </div>

    <div class="col-lg-5">
        <div class="d-flex justify-content-between align-items-start">
            <h2><?= htmlspecialchars($photo['title'], ENT_QUOTES, 'UTF-8') ?></h2>
            <?php if (!empty($_SESSION['user_id']) && (int) $_SESSION['user_id'] === (int) $photo['user_id']): ?>
                <a href="<?= url('/photo/' . (int) $photo['id'] . '/delete') ?>" class="btn btn-outline-danger btn-sm"
                   onclick="return confirm('Delete this photo permanently?');">Delete</a>
            <?php endif; ?>
        </div>
        <p class="text-muted mb-1">by <?= htmlspecialchars($photo['first_name'] . ' ' . $photo['last_name'], ENT_QUOTES, 'UTF-8') ?>
            &middot; <?= htmlspecialchars($photo['date_time'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php if (!empty($photo['description'])): ?>
            <p><?= nl2br(htmlspecialchars($photo['description'], ENT_QUOTES, 'UTF-8')) ?></p>
        <?php endif; ?>

        <?php if (!empty($tags)): ?>
            <p class="mb-2">
                <span class="text-muted small">Tagged:</span>
                <?php foreach ($tags as $t): ?>
                    <span class="badge bg-secondary-subtle text-dark border me-1">
                        <?= htmlspecialchars($t['first_name'] . ' ' . $t['last_name'], ENT_QUOTES, 'UTF-8') ?>
                    </span>
                <?php endforeach; ?>
            </p>
        <?php endif; ?>

        <hr>

        <h5>Comments (<?= count($comments) ?>)</h5>
        <div class="mb-3" style="max-height:320px;overflow-y:auto;" id="commentsList">
            <?php if (empty($comments)): ?>
                <p class="text-muted small">No comments yet.</p>
            <?php else: ?>
                <?php foreach ($comments as $c): ?>
                    <div class="border-bottom py-2">
                        <strong><?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name'], ENT_QUOTES, 'UTF-8') ?></strong>
                        <span class="text-muted small">&middot; <?= htmlspecialchars($c['date_time'], ENT_QUOTES, 'UTF-8') ?></span>
                        <p class="mb-0"><?= nl2br(htmlspecialchars($c['comment'], ENT_QUOTES, 'UTF-8')) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if (!empty($_SESSION['flash_errors'])): ?>
            <div class="alert alert-danger small">
                <?php foreach ($_SESSION['flash_errors'] as $err): ?>
                    <div><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endforeach; unset($_SESSION['flash_errors']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['user_id'])): ?>
            <form action="<?= url('/photo/' . (int) $photo['id'] . '/comment') ?>" method="POST" id="commentForm" novalidate>
                <div class="input-group">
                    <input type="text" name="comment" class="form-control" placeholder="Add a comment..." required minlength="1">
                    <button type="submit" class="btn btn-primary">Post</button>
                </div>
            </form>
        <?php else: ?>
            <p class="small"><a href="<?= url('/login') ?>">Log in</a> to add a comment.</p>
        <?php endif; ?>
    </div>
</div>
