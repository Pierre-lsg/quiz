<?php

namespace App\Controller;

use App\Entity\ResultComment;
use App\Form\ResultCommentType;
use App\Repository\ResultCommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/result/comment")
 */
class ResultCommentController extends AbstractController
{
    /**
     * @Route("/", name="result_comment_index", methods={"GET"})
     */
    public function index(ResultCommentRepository $resultCommentRepository): Response
    {
        return $this->render('result_comment/index.html.twig', [
            'result_comments' => $resultCommentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="result_comment_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $resultComment = new ResultComment();
        $form = $this->createForm(ResultCommentType::class, $resultComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($resultComment);
            $entityManager->flush();

            return $this->redirectToRoute('result_comment_index');
        }

        return $this->render('result_comment/new.html.twig', [
            'result_comment' => $resultComment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="result_comment_show", methods={"GET"})
     */
    public function show(ResultComment $resultComment): Response
    {
        return $this->render('result_comment/show.html.twig', [
            'result_comment' => $resultComment,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="result_comment_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ResultComment $resultComment): Response
    {
        $form = $this->createForm(ResultCommentType::class, $resultComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('result_comment_index');
        }

        return $this->render('result_comment/edit.html.twig', [
            'result_comment' => $resultComment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="result_comment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ResultComment $resultComment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$resultComment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($resultComment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('result_comment_index');
    }
}
