<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alzikrayat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= url('/css/style.css') ?>">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= url('/') ?>">Alzikrayat</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link<?= strpos($_SERVER['REQUEST_URI'], 'photos') === false && strpos($_SERVER['REQUEST_URI'], 'photo/') === false ? ' active' : '' ?>" href="<?= url('/') ?>">Home</a></li>
                <li class="nav-item"><a class="nav-link<?= (strpos($_SERVER['REQUEST_URI'], 'photo') !== false && strpos($_SERVER['REQUEST_URI'], 'create') === false) ? ' active' : '' ?>" href="<?= url('/photos') ?>">Gallery</a></li>
                <?php if (!empty($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link<?= strpos($_SERVER['REQUEST_URI'], 'create') !== false ? ' active' : '' ?>" href="<?= url('/photo/create') ?>">Upload Photo</a></li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <?php if (!empty($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a href="<?= url('/profile') ?>" class="d-flex align-items-center text-light me-3 text-decoration-none">
                           <span class="d-inline-flex align-items-center justify-content-center rounded-circle me-2"
                               style="width:28px;height:28px;background:var(--accent);color:var(--primary);font-weight:bold;font-size:0.85rem;">
                            <?= strtoupper(substr($_SESSION['first_name'], 0, 1)) ?>
                           </span>
                           <?= htmlspecialchars($_SESSION['first_name'], ENT_QUOTES, 'UTF-8') ?> ,Hi
                        </a>
                    </li>
                    
                <?php else: ?>
                    <li class="nav-item">
                        <span class="navbar-text text-light me-3">Please Login</span>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-light btn-sm" href="<?= url('/login') ?>">Login / Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">
