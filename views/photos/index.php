<h2 class="mb-0">Gallery</h2>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">

    <form action="<?= url('/photos') ?>" method="GET" class="d-flex" style="max-width:420px;">
        <input type="text" name="q" class="form-control me-2"
               placeholder="Search by title..."
               value="<?= htmlspecialchars($query ?? '', ENT_QUOTES, 'UTF-8') ?>">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <div class="btn-group" role="group" aria-label="Display style">
        <a href="?style=grid3" class="btn btn-sm btn-outline-primary <?= $style === 'grid3' ? 'active' : '' ?>">3-Column</a>
        <a href="?style=grid4" class="btn btn-sm btn-outline-primary <?= $style === 'grid4' ? 'active' : '' ?>">4-Column</a>
        <a href="?style=list" class="btn btn-sm btn-outline-primary <?= $style === 'list' ? 'active' : '' ?>">List</a>
        <a href="?style=slider" class="btn btn-sm btn-outline-primary <?= $style === 'slider' ? 'active' : '' ?>">Slider</a>
    </div>

</div>


<?php if (empty($photos)): ?>
    <div class="alert alert-secondary">No photos have been uploaded yet. Be the first to
        <a href="<?= url('/photo/create') ?>">share a memory</a>!</div>
<?php elseif ($style === 'slider'): ?>
    <div id="photoSlider" class="carousel slide shadow-sm rounded overflow-hidden" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php foreach ($photos as $i => $p): ?>
                <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                    <a href="<?= url('/photo/' . (int) $p['id']) ?>">
                        <img src="<?= url('/images/uploads/' . htmlspecialchars($p['file_name'], ENT_QUOTES, 'UTF-8')) ?>"
                             class="d-block w-100" style="max-height:500px;object-fit:cover;"
                             alt="<?= htmlspecialchars($p['title'], ENT_QUOTES, 'UTF-8') ?>">
                    </a>
                    <div class="carousel-caption bg-dark bg-opacity-50 rounded p-2">
                        <h5><?= htmlspecialchars($p['title'], ENT_QUOTES, 'UTF-8') ?></h5>
                        <p class="small mb-0">by <?= htmlspecialchars($p['first_name'] . ' ' . $p['last_name'], ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#photoSlider" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#photoSlider" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
<?php elseif ($style === 'list'): ?>
    <div class="list-group">
        <?php foreach ($photos as $p): ?>
            <a href="<?= url('/photo/' . (int) $p['id']) ?>" class="list-group-item list-group-item-action d-flex gap-3 align-items-center">
                <img src="<?= url('/images/uploads/' . htmlspecialchars($p['file_name'], ENT_QUOTES, 'UTF-8')) ?>"
                     class="rounded" style="width:80px;height:80px;object-fit:cover;"
                     alt="<?= htmlspecialchars($p['title'], ENT_QUOTES, 'UTF-8') ?>">
                <div>
                    <h6 class="mb-1"><?= htmlspecialchars($p['title'], ENT_QUOTES, 'UTF-8') ?></h6>
                    <small class="text-muted">by <?= htmlspecialchars($p['first_name'] . ' ' . $p['last_name'], ENT_QUOTES, 'UTF-8') ?>
                        &middot; <?= htmlspecialchars($p['date_time'], ENT_QUOTES, 'UTF-8') ?></small>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <?php $cols = $style === 'grid4' ? 'col-6 col-md-3' : 'col-6 col-md-4'; ?>
    <div class="row g-3">
        <?php foreach ($photos as $p): ?>
            <div class="<?= $cols ?>">
                <a href="<?= url('/photo/' . (int) $p['id']) ?>" class="text-decoration-none text-dark">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= url('/images/uploads/' . htmlspecialchars($p['file_name'], ENT_QUOTES, 'UTF-8')) ?>"
                             class="card-img-top" style="aspect-ratio:1/1;object-fit:cover;"
                             alt="<?= htmlspecialchars($p['title'], ENT_QUOTES, 'UTF-8') ?>">
                        <div class="card-body p-2">
                            <p class="mb-0 small fw-semibold text-truncate"><?= htmlspecialchars($p['title'], ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="mb-0 small text-muted text-truncate">by <?= htmlspecialchars($p['first_name'], ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
