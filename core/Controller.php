<?php

/**
 * Class Controller (Abstract Base Controller)
 *
 * Provides shared helpers used by every concrete controller:
 * rendering views with layout wrapping, redirecting, and simple
 * auth-guard checks against the PHP session.
 *
 * @package core
 */
abstract class Controller
{
    /**
     * Renders a view file, optionally wrapped between the shared
     * header/footer layout partials.
     *
     * @param string $view    Dot-free relative path under /views, e.g. "photos/index"
     * @param array  $data    Associative array extracted into local view variables
     * @param bool   $layout  Whether to wrap the view with header/footer
     * @return void
     */
    protected function render(string $view, array $data = [], bool $layout = true): void
    {
        extract($data, EXTR_SKIP);

        $viewFile = __DIR__ . "/../views/{$view}.php";

        if (!file_exists($viewFile)) {
            http_response_code(500);
            die("View not found: {$view}");
        }

        if ($layout) {
            require __DIR__ . '/../views/layout/header.php';
        }

        require $viewFile;

        if ($layout) {
            require __DIR__ . '/../views/layout/footer.php';
        }
    }

    /**
     * Sends an HTTP redirect to a given internal path and halts execution.
     * The path is automatically prefixed with the app's detected base
     * path (see public/index.php / core/helpers.php), so callers just
     * pass a plain root-relative path like "/photos".
     *
     * @param string $path e.g. "/photos" or "/login"
     * @return never
     */
    protected function redirect(string $path)
    {
        header('Location: ' . url($path));
        exit;
    }

    /**
     * Guards an action so only authenticated users may proceed.
     * Redirects to the login page and halts if no active session exists.
     *
     * @return void
     */
    protected function requireAuth(): void
    {
        if (empty($_SESSION['user_id'])) {
            $_SESSION['flash_error'] = 'Please log in to continue.';
            $this->redirect('/login');
        }
    }

    /**
     * Escapes a string for safe HTML output (XSS defense).
     *
     * @param string|null $value
     * @return string
     */
    protected function e(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}
