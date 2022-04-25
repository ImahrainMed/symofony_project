<?php


namespace App\Service;


use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;

class CommentService
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * flush and persist comment to database
     * @param Comment $comment
     */
    public function saveComment(Comment $comment):void
    {
        if($comment->getId() === null)
        {
            $this->entityManager->persist($comment);
        }

        $this->entityManager->flush();
    }
}