<?php

namespace Repository;

use Entity\Comment;

class CommentRepository extends BaseRepository
{
    public function createComment(Comment $comment)
    {
        $sql = "INSERT INTO comments (target_id, target_type, username, content)
                VALUES (:target_id, :target_type, :username, :content)";

        $params = [
            ':target_id' => $comment->getTargetId(),
            ':target_type' => $comment->getTargetType(),
            ':username' => $comment->getUsername(),
            ':content' => $comment->getContent(),
        ];

        $this->executeQuery($sql, $params);

        // Return the ID of the newly created record
        return $this->connection->lastInsertId();
    }

    public function getCommentsByTarget($targetId, $targetType)
    {
        $sql = "SELECT * FROM comments 
                WHERE target_id = :target_id AND target_type = :target_type 
                ORDER BY created_at ASC";

        $params = [
            ':target_id' => $targetId,
            ':target_type' => $targetType,
        ];

        $rows = $this->fetchAll($sql, $params);

        $comments = [];
        foreach ($rows as $row) {
            $comments[] = new Comment(
                $row['id'],
                $row['target_id'],
                $row['target_type'],
                $row['username'],
                $row['content'],
            );
        }

        return $comments;
    }


}
