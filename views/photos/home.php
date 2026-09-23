<div class="p-5 mb-4 rounded-4 text-center" >
    <h1 class="display-5 fw-bold" style="color: var(--primary);">Welcome to Alzikrayat</h1>
    <p class="lead mb-2" style="color: var(--primary-dark);">"Alzikrayat" means <em>memories</em>.Memories make life special</p>
    <p class="lead mb-4" style="color: var(--primary-dark);"> With Alzikrayat you can share, 
    upload, browse and comment on the special moments that matter to you and your friends.</p>
   <?php if (empty($_SESSION['user_id'])): ?>
    <a href="<?= url('/register') ?>">Join Now</a>
   <?php else: ?>
    <a href="<?= url('/photo/create') ?>">Upload a Photo</a>
   <?php endif; ?>
</div>

<div class="row text-center mb-5 g-3">
    <div class="col-md-6">
        <div class="card shadow-sm p-4">
            <h2 class="fw-bold text-primary"><?= (int) $totalPhotos ?></h2>
            <p class="text-muted mb-0">Photos Shared</p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm p-4">
            <h2 class="fw-bold text-primary"><?= (int) $totalUsers ?></h2>
            <p class="text-muted mb-0">Active Members</p>
        </div>
    </div>
</div>

<?php if (!empty($recentPhotos)): ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Recent Memories</h3>
    <a href="<?= url('/photos') ?>" class="btn btn-primary btn-lg">Browse Gallery</a>
</div>
<div class="row g-3 mb-5">
    <?php foreach ($recentPhotos as $p): ?>
        <div class="col-6 col-md-4 col-lg-2">
            <a href="<?= url('/photo/' . (int) $p['id']) ?>">
                <img src="<?= url('/images/uploads/' . htmlspecialchars($p['file_name'], ENT_QUOTES, 'UTF-8')) ?>"
                     class="img-fluid rounded shadow-sm" style="aspect-ratio:1/1;object-fit:cover;width:100%;"
                     alt="<?= htmlspecialchars($p['title'], ENT_QUOTES, 'UTF-8') ?>">
            </a>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="card shadow-sm p-4" id="about">
    <h3>About Us</h3>
      <p class="mb-2">We believe that memories are an important part of our lives.</p>
      <p class="mb-2">Every photo captures a special moment, a feeling, or a story worth remembering.</p>
      <p class="mb-2">Alzikrayat is a simple, fast place to upload, browse, comment and share their favorite photos and memories with your friends.</p>
      <p class="mb-0">Our goal is to help users keep their special moments organized and easily revisit them whenever they want.</p>    
</div>
