<?php

namespace Service;

use Repository\CommentRepository;
use Entity\Comment;

class CommentService
{
    private CommentRepository $commentRepository;

    public function __construct()
    {
        $this->commentRepository = new CommentRepository();
    }

    public function createComment(Comment $comment)
    {
        // Validate comment content
        if (empty($comment->getContent())) {
            throw new \InvalidArgumentException('Comment content cannot be empty.');
        }

        // Validate target
        if (empty($comment->getTargetId()) || empty($comment->getTargetType())) {
            throw new \InvalidArgumentException('Target ID and type must be specified.');
        }

        // Validate user ID
        if (empty($comment->getUserId())) {
            throw new \InvalidArgumentException('User ID cannot be empty.');
        }

        return $this->commentRepository->createComment($comment);
    }

    public function getCommentsByTarget($targetId, $targetType)
    {
        // Validate target ID and type
        if (empty($targetId) || empty($targetType)) {
            throw new \InvalidArgumentException('Target ID and type must be specified.');
        }

        // Retrieve comments using the repository
        return $this->commentRepository->getCommentsByTarget($targetId, $targetType);
    }
}
