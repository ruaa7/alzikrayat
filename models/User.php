<?php
require_once __DIR__ . '/../core/Model.php';

/**
 * Class User
 *
 * Data-access model for the `users` table. All queries are raw,
 * parameterized PDO statements -- no ORM is used.
 *
 * @package models
 */
class User extends Model
{
    /**
     * Validates registration input.
     *
     * @param array $data Raw $_POST input: first_name, last_name, email, password
     * @return array List of human-readable error strings (empty = valid)
     */
    public static function validate(array $data): array
    {
        $errors = [];

        if (empty($data['first_name']) || !preg_match('/^[\p{L} ]{1,50}$/u', $data['first_name'])) {
            $errors[] = 'First name is required and must contain letters only (max 50 chars).';
        }

        if (empty($data['last_name']) || !preg_match('/^[\p{L} ]{1,50}$/u', $data['last_name'])) {
            $errors[] = 'Last name is required and must contain letters only (max 50 chars).';
        }

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid email address is required.';
        }

        if (empty($data['password']) || strlen($data['password']) < 6) {
            $errors[] = 'Password is required and must be at least 6 characters.';
        }

        return $errors;
    }

    /**
     * Inserts a new user with a securely hashed password.
     *
     * @param array $data first_name, last_name, email, password (plain), location?, description?, occupation?
     * @return int Newly created user id
     */
    public function create(array $data): int
    {
        $sql = "INSERT INTO users (first_name, last_name, email, password, location, description, occupation)
                VALUES (:first_name, :last_name, :email, :password, :location, :description, :occupation)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':first_name'  => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_BCRYPT),
            ':location'  => $data['location'] ?? null,
            ':description' => $data['description'] ?? null,
            ':occupation'  => $data['occupation'] ?? null,
        ]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Finds a user by email (used during login and uniqueness checks).
     *
     * @param string $email
     * @return array|null
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Finds a user by primary key id.
     *
     * @param int $id
     * @return array|null
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Verifies a plain-text password against a stored bcrypt hash.
     *
     * @param string $plain
     * @param string $hash
     * @return bool
     */
    public function verifyPassword(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }

    /**
     * Returns every registered user except the given id, ordered by
     * first name. Used to populate the "tag people" list on the photo
     * upload form (novelty feature).
     *
     * @param int $excludeId
     * @return array
     */
    public function findAllExcept(int $excludeId): array
    {
        $stmt = $this->db->prepare(
            'SELECT id, first_name, last_name FROM users WHERE id != :id ORDER BY first_name ASC'
        );
        $stmt->execute([':id' => $excludeId]);
        return $stmt->fetchAll();
    }
}
