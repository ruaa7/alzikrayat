<?php
require_once __DIR__ . '/../core/Model.php';

/**
 * Class PhotoTag
 *
 * Data-access model for the `photo_tags` table. Implements the
 * "tagging other registered users in photos" novelty feature suggested
 * by the specification (Section 4.5).
 *
 * @package models
 */
class PhotoTag extends Model
{
    /**
     * Tags a list of user ids in a photo. Silently skips ids that are
     * already tagged on this photo (unique key on photo_id+user_id)
     * and ignores the uploader tagging themselves twice.
     *
     * @param int   $photoId
     * @param array $userIds   List of user ids to tag (as strings or ints)
     * @param int   $taggedBy  The id of the user performing the tagging (uploader)
     * @return void
     */
    public function tagUsers(int $photoId, array $userIds, int $taggedBy): void
    {
        $sql = "INSERT IGNORE INTO photo_tags (photo_id, user_id, tagged_by)
                VALUES (:photo_id, :user_id, :tagged_by)";
        $stmt = $this->db->prepare($sql);

        foreach ($userIds as $userId) {
            $userId = (int) $userId;
            if ($userId <= 0) {
                continue;
            }
            $stmt->execute([
                ':photo_id'  => $photoId,
                ':user_id'   => $userId,
                ':tagged_by' => $taggedBy,
            ]);
        }
    }

    /**
     * Returns every user tagged in a given photo, with their names.
     *
     * @param int $photoId
     * @return array
     */
    public function findByPhotoId(int $photoId): array
    {
        $sql = "SELECT t.user_id, u.first_name, u.last_name
                FROM photo_tags t
                JOIN users u ON u.id = t.user_id
                WHERE t.photo_id = :pid
                ORDER BY u.first_name ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':pid' => $photoId]);
        return $stmt->fetchAll();
    }

    /**
     * Removes a single tag (used if an uploader wants to untag someone).
     *
     * @param int $photoId
     * @param int $userId
     * @return bool
     */
    public function untag(int $photoId, int $userId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM photo_tags WHERE photo_id = :pid AND user_id = :uid');
        return $stmt->execute([':pid' => $photoId, ':uid' => $userId]);
    }
}
