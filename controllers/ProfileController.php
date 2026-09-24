<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Photo.php';

class ProfileController extends Controller
{
    public function show(string $id): void
    {
        $userModel = new User();
        $user = $userModel->findById((int) $id);

        if (!$user) {
            http_response_code(404);
            $this->render('photos/not_found', []);
            return;
        }

        $photoModel = new Photo();
        $photos = $photoModel->findByUser((int) $id);

        $this->render('profile/show', [
            'profileUser' => $user,
            'photos'      => $photos,
        ]);
    }

    public function myProfile(): void
    {
        $this->requireAuth();
        $this->show((string) $_SESSION['user_id']);
    }
}
