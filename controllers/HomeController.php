<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Photo.php';
require_once __DIR__ . '/../core/Model.php';

/**
 * Class HomeController
 *
 * Serves the application's welcoming entrance page: a summary of the
 * project, a few basic statistics pulled from the database, and a
 * static "About Us" section, with navigation into the gallery.
 *
 * @package controllers
 */
class HomeController extends Controller
{
    /**
     * GET / - landing page.
     *
     * @return void
     */
    public function index(): void
    {
        $photoModel = new Photo();
        $allPhotos  = $photoModel->findAll();

        $stats = [
            'totalPhotos'   => count($allPhotos),
            'totalUsers'    => count(array_unique(array_column($allPhotos, 'user_id'))),
            'recentPhotos'  => array_slice($allPhotos, 0, 6),
        ];

        $this->render('photos/home', $stats);
    }
}
