<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Entity\Post;

final class PostController extends AbstractController
{
    #[Route('/post/{id}', name: 'app_post', methods: ['GET', 'POST'])]
    public function show(Post $post, Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $comment  = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setPost($post);
            $entityManagerInterface->persist($comment);

            $entityManagerInterface->flush();

            return $this->redirectToRoute('app_post', ['id' => $post->getId()]);
        }

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'commentForm' => $commentForm
        ]);
    }
}