<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';

/**
 * Class AuthController
 *
 * Handles registration, login, logout, session-based authentication
 * state, and the persistent "last login" cookie required by the
 * specification (7-day expiry, shown on the login page).
 *
 * @package controllers
 */
class AuthController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * GET /register - shows the registration form.
     *
     * @return void
     */
    public function showRegister(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect('/photos');
        }
        $this->render('auth/register', ['errors' => $_SESSION['flash_errors'] ?? []]);
        unset($_SESSION['flash_errors']);
    }

    /**
     * POST /register - validates and creates a new user account.
     *
     * @return void
     */
    public function register(): void
    {
        $data = [
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name'  => trim($_POST['last_name'] ?? ''),
            'email'      => trim($_POST['email'] ?? ''),
            'password'   => $_POST['password'] ?? '',
            'location'   => trim($_POST['location'] ?? ''),
            'description'=> trim($_POST['description'] ?? ''),
            'occupation' => trim($_POST['occupation'] ?? ''),
        ];

        $errors = User::validate($data);

        if (empty($errors) && $this->userModel->findByEmail($data['email'])) {
            $errors[] = 'This email is already registered.';
        }

        if (!empty($errors)) {
            $_SESSION['flash_errors'] = $errors;
            $this->redirect('/register');
        }

        $userId = $this->userModel->create($data);

        $_SESSION['user_id']    = $userId;
        $_SESSION['first_name'] = $data['first_name'];

        $this->setLastLoginCookie();
        $this->redirect('/photos');
    }

    /**
     * GET /login - shows the login form, including the "last login from
     * this computer" reminder read from the persistent cookie.
     *
     * @return void
     */
    public function showLogin(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect('/photos');
        }

        $lastLogin = $_COOKIE['last_login'] ?? null;

        $this->render('auth/login', [
            'errors'    => $_SESSION['flash_errors'] ?? [],
            'lastLogin' => $lastLogin,
        ]);
        unset($_SESSION['flash_errors']);
    }

    /**
     * POST /login - authenticates the user, starts the session, and
     * (re)sets the 7-day "last login" cookie on success.
     *
     * @return void
     */
    public function login(): void
    {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->findByEmail($email);

        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            $_SESSION['flash_errors'] = ['Invalid email or password.'];
            $this->redirect('/login');
        }

        $_SESSION['user_id']    = $user['id'];
        $_SESSION['first_name'] = $user['first_name'];

        $this->setLastLoginCookie();
        $this->redirect('/photos');
    }

    /**
     * GET /logout - destroys the session (the last_login cookie is
     * intentionally left intact, since it must persist independently
     * of session state per the specification).
     *
     * @return void
     */
    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        $this->redirect('/login');
    }

    /**
     * Sets (or refreshes) the "last_login" cookie with the current
     * timestamp, expiring 7 days from now.
     *
     * @return void
     */
    private function setLastLoginCookie(): void
    {
        setcookie(
            'last_login',
            date('Y-m-d H:i:s'),
            [
                'expires'  => time() + (7 * 24 * 60 * 60),
                'path'     => '/',
                'httponly' => true,
                'samesite' => 'Lax',
            ]
        );
    }
}
