<?php
require_once __DIR__ . '/../core/Model.php';

/**
 * Class Comment
 *
 * Data-access model for the `comments` table.
 *
 * @package models
 */
class Comment extends Model
{
    /**
     * Validates comment text.
     *
     * @param array $data comment
     * @return array List of error strings
     */
    public static function validate(array $data): array
    {
        $errors = [];

        if (empty(trim($data['comment'] ?? ''))) {
            $errors[] = 'Comment text cannot be empty.';
        }

        return $errors;
    }

    /**
     * Inserts a new comment tied to a photo and the authoring user.
     *
     * @param array $data photo_id, user_id, comment
     * @return int Newly created comment id
     */
    public function create(array $data): int
    {
        $sql = "INSERT INTO comments (photo_id, user_id, comment)
                VALUES (:photo_id, :user_id, :comment)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':photo_id' => $data['photo_id'],
            ':user_id'  => $data['user_id'],
            ':comment'  => $data['comment'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Returns every comment for a photo, oldest first, joined with the
     * commenting user's name.
     *
     * @param int $photoId
     * @return array
     */
    public function findByPhotoId(int $photoId): array
    {
        $sql = "SELECT c.*, u.first_name, u.last_name
                FROM comments c
                JOIN users u ON u.id = c.user_id
                WHERE c.photo_id = :pid
                ORDER BY c.date_time ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':pid' => $photoId]);
        return $stmt->fetchAll();
    }
}
