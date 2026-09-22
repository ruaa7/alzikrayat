<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm p-4">
            <h2 class="mb-3 text-center">Login</h2>

            <?php if (!empty($lastLogin)): ?>
                <div class="alert alert-info small">
                    Last login from this computer was <strong><?= htmlspecialchars($lastLogin, ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $err): ?>
                            <li><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= url('/login') ?>" method="POST" novalidate id="loginForm">
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <div class="invalid-feedback">Please enter a valid email address.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required minlength="6">
                    <div class="invalid-feedback">Password must be at least 6 characters.</div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

            <p class="text-center mt-3 mb-0">
                Don't have an account? <a href="<?= url('/register') ?>">Register here</a>
            </p>
        </div>
    </div>
</div>
