<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card shadow-sm p-4">
            <h2 class="mb-3 text-center">Create an Account</h2>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $err): ?>
                            <li><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= url('/register') ?>" method="POST" novalidate id="registerForm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="first_name">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name"
                               pattern="[A-Za-z ]{1,50}" required>
                        <div class="invalid-feedback">Letters only, max 50 characters.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="last_name">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name"
                               pattern="[A-Za-z ]{1,50}" required>
                        <div class="invalid-feedback">Letters only, max 50 characters.</div>
                    </div>
                </div>
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
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="location">Location <span class="text-muted small">(optional)</span></label>
                        <input type="text" class="form-control" id="location" name="location" maxlength="100">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="occupation">Occupation <span class="text-muted small">(optional)</span></label>
                        <input type="text" class="form-control" id="occupation" name="occupation" maxlength="100">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="description">Bio <span class="text-muted small">(optional)</span></label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>

            <p class="text-center mt-3 mb-0">
                Already have an account? <a href="<?= url('/login') ?>">Login here</a>
            </p>
        </div>
    </div>
</div>
