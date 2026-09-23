<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Photo.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../models/PhotoTag.php';
require_once __DIR__ . '/../models/User.php';

/**
 * Class PhotoController
 *
 * Handles the photo gallery, upload, detailed view, and deletion.
 *
 * @package controllers
 */
class PhotoController extends Controller
{
    private Photo $photoModel;

    /** @var string Absolute path to the uploads directory on disk */
    private string $uploadDir;

    /** @var array<string> Allowed image MIME types for uploads */
    private array $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    public function __construct()
    {
        $this->photoModel = new Photo();
        $this->uploadDir  = __DIR__ . '/../public/images/uploads/';
    }

    /**
     * GET /photos - main gallery. Supports a "style" query param to
     * switch between grid layouts (grid3, grid4, list, slider) as
     * required by the "custom display styles" requirement.
     *
     * @return void
     */
    public function index(): void
    {
        $photos = $this->photoModel->findAll();
        $style  = $_GET['style'] ?? 'grid3';
        $allowedStyles = ['grid3', 'grid4', 'list', 'slider'];
        if (!in_array($style, $allowedStyles, true)) {
            $style = 'grid3';
        }

        $this->render('photos/index', [
            'photos' => $photos,
            'style'  => $style,
        ]);
    }

    /**
     * GET /photo/{id} - detailed view with metadata and comments.
     *
     * @param string $id
     * @return void
     */
    public function show(string $id): void
    {
        $photo = $this->photoModel->findById((int) $id);

        if (!$photo) {
            http_response_code(404);
            $this->render('photos/not_found', []);
            return;
        }

        $commentModel = new Comment();
        $comments = $commentModel->findByPhotoId((int) $id);

        $tagModel = new PhotoTag();
        $tags = $tagModel->findByPhotoId((int) $id);

        $this->render('photos/show', [
            'photo'    => $photo,
            'comments' => $comments,
            'tags'     => $tags,
        ]);
    }

    /**
     * GET /photo/create - shows the upload form, including the list of
     * other registered users available to tag. Auth required.
     *
     * @return void
     */
    public function create(): void
    {
        $this->requireAuth();

        $userModel = new User();
        $taggableUsers = $userModel->findAllExcept((int) $_SESSION['user_id']);

        $this->render('photos/create', [
            'errors'        => $_SESSION['flash_errors'] ?? [],
            'taggableUsers' => $taggableUsers,
        ]);
        unset($_SESSION['flash_errors']);
    }

    /**
     * POST /photo/store - handles the multipart upload: validates the
     * file and metadata, stores the file on disk, then persists the
     * metadata row. Auth required.
     *
     * @return void
     */
    public function store(): void
    {
        $this->requireAuth();

        $data = [
            'title'       => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
        ];

        $errors = Photo::validate($data);

        if (empty($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Please choose a valid image file to upload.';
        } else {
            $mime = mime_content_type($_FILES['photo']['tmp_name']);
            if (!in_array($mime, $this->allowedTypes, true)) {
                $errors[] = 'Only JPG, PNG, GIF, or WEBP images are allowed.';
            }
            if ($_FILES['photo']['size'] > 5 * 1024 * 1024) {
                $errors[] = 'Image must be smaller than 5MB.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['flash_errors'] = $errors;
            $this->redirect('/photo/create');
        }

        // Build a safe, collision-resistant file name.
        $extension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $safeExtension = preg_replace('/[^a-z0-9]/', '', $extension);
        $fileName = 'photo_' . bin2hex(random_bytes(8)) . '.' . $safeExtension;
        $destination = $this->uploadDir . $fileName;

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
            $_SESSION['flash_errors'] = ['Failed to save the uploaded file.'];
            $this->redirect('/photo/create');
        }

        $photoId = $this->photoModel->create([
            'user_id'     => $_SESSION['user_id'],
            'file_name'   => $fileName,
            'title'       => $data['title'],
            'description' => $data['description'],
        ]);

        // Novelty feature: tag other registered users in this photo.
        // $_POST['tags'] is an array of user ids from the multi-select
        // checkboxes on the upload form (optional).
        if (!empty($_POST['tags']) && is_array($_POST['tags'])) {
            $tagModel = new PhotoTag();
            $tagModel->tagUsers($photoId, $_POST['tags'], (int) $_SESSION['user_id']);
        }

        $this->redirect('/photo/' . $photoId);
    }

    /**
     * GET /photo/{id}/delete - deletes a photo after verifying the
     * current session user owns it. Removes both the DB row and the
     * physical file.
     *
     * @param string $id
     * @return void
     */
    public function delete(string $id): void
    {
        $this->requireAuth();

        $photo = $this->photoModel->findById((int) $id);

        if (!$photo) {
            http_response_code(404);
            die('Photo not found.');
        }

        // Ownership check: a user must NOT be able to delete another
        // user's photo.
        if ((int) $photo['user_id'] !== (int) $_SESSION['user_id']) {
            http_response_code(403);
            die('You are not allowed to delete this photo.');
        }

        $filePath = $this->uploadDir . $photo['file_name'];
        if (is_file($filePath)) {
            unlink($filePath);
        }

        $this->photoModel->delete((int) $id);
        $this->redirect('/photos');
    }
}
