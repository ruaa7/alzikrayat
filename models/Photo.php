<?php
require_once __DIR__ . '/../core/Model.php';

/**
 * Class Photo
 *
 * Data-access model for the `photos` table.
 *
 * @package models
 */
class Photo extends Model
{
    /**
     * Validates photo upload metadata (not the file itself).
     *
     * @param array $data title, description?
     * @return array List of error strings
     */
    public static function validate(array $data): array
    {
        $errors = [];

        if (empty($data['title']) || strlen($data['title']) > 200) {
            $errors[] = 'A title is required (max 200 characters).';
        }

        return $errors;
    }

    /**
     * Inserts a new photo record.
     *
     * @param array $data user_id, file_name, title, description?
     * @return int Newly created photo id
     */
    public function create(array $data): int
    {
        $sql = "INSERT INTO photos (user_id, file_name, title, description)
                VALUES (:user_id, :file_name, :title, :description)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':user_id'     => $data['user_id'],
            ':file_name'   => $data['file_name'],
            ':title'       => $data['title'],
            ':description' => $data['description'] ?? null,
        ]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Returns every photo joined with its uploader's name, newest first,
     * for the main gallery view.
     *
     * @return array
     */
    public function findAll(): array
    {
        $sql = "SELECT p.*, u.first_name, u.last_name
                FROM photos p
                JOIN users u ON u.id = p.user_id
                ORDER BY p.date_time DESC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Searches photos by title using a case-insensitive partial match,
     * newest first. Uses a parameterized LIKE query -safe from SQL
     * and reused by the gallery search box.
     *
     * @param string $query The search term typed by the user
     * @return array
     */
    public function searchByTitle(string $query): array
    {
            $sql = "SELECT p.*, u.first_name, u.last_name
               FROM photos p
               JOIN users u ON u.id = p.user_id
               WHERE p.title LIKE :query
               ORDER BY p.date_time DESC";

          $stmt = $this->db->prepare($sql);
          $stmt->execute([':query' => '%' . $query . '%']);
          return $stmt->fetchAll();
   }
 
    /**
     * Finds a single photo (with uploader info) by id.
     *
     * @param int $id
     * @return array|null
     */
    public function findById(int $id): ?array
    {
        $sql = "SELECT p.*, u.first_name, u.last_name
                FROM photos p
                JOIN users u ON u.id = p.user_id
                WHERE p.id = :id
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Returns all photos belonging to a given user.
     *
     * @param int $userId
     * @return array
     */
    public function findByUser(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM photos WHERE user_id = :uid ORDER BY date_time DESC');
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll();
    }

    /**
     * Deletes a photo row by id. Caller is responsible for verifying
     * ownership BEFORE calling this method, and for removing the
     * physical file from disk.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM photos WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
