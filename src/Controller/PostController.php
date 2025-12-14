<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\File;
use App\Entity\Post;
use App\Entity\Post_file;
use App\Form\Type\CommentType;
use App\Form\Type\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostController extends AbstractController
{
    #[Route('/post/create', name: 'app_post_create')]
    public function create(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUserId($this->getUser());
            $entityManager->persist($post);
            $entityManager->flush();

            $files = $form->get('files')->getData();
            if ($files) {
                $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                foreach ($files as $uploadedFile) {
                    if ($uploadedFile) {
                        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                        $safeFilename = $slugger->slug($originalFilename);
                        $fileName = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

                        try {
                            $uploadedFile->move($uploadDir, $fileName);
                        } catch (FileException $e) {
                            continue;
                        }

                        $file = new File();
                        $file->setPath('/uploads/' . $fileName);
                        $file->setOriginalName($uploadedFile->getClientOriginalName());
                        $entityManager->persist($file);

                        $postFile = new Post_file();
                        $postFile->setPostId($post);
                        $postFile->setFileId($file);
                        $entityManager->persist($postFile);
                    }
                }
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_home_index');
        }

        return $this->render('Post/NewPost.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/post/{id}', name: 'app_post_show')]
    public function show(Post $post): Response
    {
        return $this->render('Post/Post.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/post/{id}/comment/new', name: 'app_comment_new')]
    public function newComment(Post $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $comment->setPostId($post);
        $comment->setUserId($this->getUser());

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_post_show', ['id' => $post->getId()]);
        }

        return $this->render('Post/NewComment.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }
}
