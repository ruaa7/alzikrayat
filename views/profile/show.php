<div class="mb-4">
        <div class="card shadow-sm p-4 text-center">
            <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle"
                 style="width:90px;height:90px;background:var(--accent);color:var(--primary);font-size:2rem;font-weight:bold;">
                <?= strtoupper(substr($profileUser['first_name'], 0, 1)) ?>
            </div>
            <h3 class="mb-1"><?= htmlspecialchars($profileUser['first_name'] . ' ' . $profileUser['last_name'], ENT_QUOTES, 'UTF-8') ?></h3>

            <?php if (!empty($profileUser['occupation'])): ?>
                <p class="text-muted mb-2"><?= htmlspecialchars($profileUser['occupation'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>

            <?php if (!empty($profileUser['location'])): ?>
                <p class="small text-muted mb-3">📍 <?= htmlspecialchars($profileUser['location'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>

            <?php if (!empty($profileUser['description'])): ?>
                <hr>
                <p class="mb-0 text-start"><?= nl2br(htmlspecialchars($profileUser['description'], ENT_QUOTES, 'UTF-8')) ?></p>
            <?php endif; ?>

            <hr>
            <p class="small text-muted mb-0"><?= count($photos) ?> photo<?= count($photos) === 1 ? '' : 's' ?> shared</p>
            <div class="text-end mt-3">
              <a href="<?= url('/logout') ?>" class="btn btn-outline-danger btn-sm">Logout</a>
            </div>
        </div>
    </div>
</div>

<div>
    <h4 class="mb-3"><?= htmlspecialchars($profileUser['first_name'], ENT_QUOTES, 'UTF-8') ?>'s Photos</h4>

    <?php if (empty($photos)): ?>
        <p class="text-muted">No photos shared yet.</p>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($photos as $p): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="<?= url('/photo/' . (int) $p['id']) ?>">
                        <img src="<?= url('/images/uploads/' . htmlspecialchars($p['file_name'], ENT_QUOTES, 'UTF-8')) ?>"
                             class="img-fluid rounded shadow-sm" style="aspect-ratio:1/1;object-fit:cover;width:100%;"
                             alt="<?= htmlspecialchars($p['title'], ENT_QUOTES, 'UTF-8') ?>">
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>