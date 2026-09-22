<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../models/Photo.php';

/**
 * Class CommentController
 *
 * Handles adding a comment to a photo. Auth required.
 *
 * @package controllers
 */
class CommentController extends Controller
{
    /**
     * POST /photo/{id}/comment - validates and stores a comment tied
     * to the current session user and the target photo, then redirects
     * back to the photo detail view.
     *
     * @param string $photoId
     * @return void
     */
    public function store(string $photoId): void
    {
        $this->requireAuth();

        $photoModel = new Photo();
        $photo = $photoModel->findById((int) $photoId);

        if (!$photo) {
            http_response_code(404);
            die('Photo not found.');
        }

        $data = ['comment' => trim($_POST['comment'] ?? '')];
        $errors = Comment::validate($data);

        if (!empty($errors)) {
            $_SESSION['flash_errors'] = $errors;
            $this->redirect('/photo/' . $photoId);
        }

        $commentModel = new Comment();
        $commentModel->create([
            'photo_id' => (int) $photoId,
            'user_id'  => $_SESSION['user_id'],
            'comment'  => $data['comment'],
        ]);

        $this->redirect('/photo/' . $photoId);
    }
}
