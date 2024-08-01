<?php

namespace App\Serializer;

class CommentSerializer
{
    public function __construct()
    {
    }

    public function serializeComments(array $comments): array
    {
        $commentsArray = array_map(function($comment) {
            return [
                'id' => $comment->getId(),
                'title' => $comment->getTitle(),
                'comment' => $comment->getComment(),
                'createdAt' => $comment->getCreatedAt()->format('Y-m-d H:i:s'),
                'modifiedAt' => $comment->getModifiedAt()->format('Y-m-d H:i:s'),
                'score' => $comment->getScore(),
                'userPseudo' => $comment->getUser()->getPseudo(),
                'townName' => $comment->getTown()->getTownName()
            ];
        }, $comments);
        return $commentsArray;
    }
}