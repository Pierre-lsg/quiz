<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Form\AnswerType;
use App\Repository\AnswerRepository;
use App\Repository\QuestionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/manage")
 */
class AnswerController extends AbstractController
{
    /**
     * @Route("/answers", name="answer_index", methods={"GET"})
     * @Route("/quiz/{idQuiz}/question/{idQuestion}/answers", name="questionAnswer_index", methods={"GET"})
     */
    public function index(AnswerRepository $answerRepository, $idQuiz = null, $idQuestion = null): Response
    {
        if ($idQuestion)
        {
            /* Récupérer les informations de la question */
            $question = new Question();
            $repo = $this->getDoctrine()->getRepository('App:Question');
            $question = $repo->find($idQuestion);

            /* Récupérer les réponses */
            $answers = $question->getAnswers();
            return $this->render('answer/index.html.twig', [
                'answers' => $answers,
                'quizId' => $idQuiz,
                'questionId' => $question->getId(),
                'questionLabel' => $question->getLabel(),
            ]);
        }
        else
        {
            $answers = $answerRepository->findAll();
            return $this->render('answer/index.html.twig', [
                'answers' => $answers,
                'quizId' => $idQuiz,
                'questionId' => null,
                'questionLabel' => null,
            ]);
        }
    }

    /**
     * @Route("/answer/new", name="answer_new", methods={"GET","POST"})
     * @Route("/quiz/{idQuiz}/question/{idQuestion}/answer/new", name="questionAnswer_new", methods={"GET","POST"})
     */
    public function new(Request $request, $idQuestion = null, $idQuiz = null, QuestionRepository $questionRepo): Response
    {
        $answer = new Answer();
        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);

        if ($idQuestion) {
            $question = $questionRepo->find($idQuestion);
            $answer->setQuestion($question);   
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($answer);
            $entityManager->flush();

            if ($idQuestion) {return $this->redirectToRoute('questionAnswer_index', ['idQuiz' => $idQuiz, 'idQuestion' => $idQuestion,]);}
            else {return $this->redirectToRoute('answer_index');}
        }

        return $this->render('answer/new.html.twig', [
            'answer' => $answer,
            'form' => $form->createView(),
            'quizId' => $idQuiz,
            'questionId' => $idQuestion,
        ]);
    }

    /**
     * @Route("/answer/{id}", name="answer_show", methods={"GET"})
     * @Route("/quiz/{idQuiz}/question/{idQuestion}/answer/{id}", name="questionAnswer_show", methods={"GET"})
     */
    public function show(Answer $answer, $idQuiz = null, $idQuestion = null): Response
    {
        return $this->render('answer/show.html.twig', [
            'answer' => $answer,
            'quizId' => $idQuiz,
            'questionId' => $idQuestion,
        ]);
    }

    /**
     * @Route("/answer/{id}/edit", name="answer_edit", methods={"GET","POST"})
     * @Route("/quiz/{idQuiz}/question/{idQuestion}/answer/{id}/edit", name="questionAnswer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Answer $answer, $idQuiz = null, $idQuestion = null, QuestionRepository $questionRepo): Response
    {
        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);

        if ($idQuestion) {
            $question = $questionRepo->find($idQuestion);
            $answer->setQuestion($question);   
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            if ($idQuestion) {return $this->redirectToRoute('questionAnswer_index', ['idQuiz' => $idQuiz, 'idQuestion' => $idQuestion,]);}
            else {return $this->redirectToRoute('answer_index');}
        }

        return $this->render('answer/edit.html.twig', [
            'answer' => $answer,
            'form' => $form->createView(),
            'quizId' => $idQuiz,
            'questionId' => $idQuestion,
        ]);
    }

    /**
     * @Route("/answer/{id}", name="answer_delete", methods={"DELETE"})
     * @Route("/quiz/{idQuiz}/question/{idQuestion}/answer/{id}", name="questionAnswer_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Answer $answer, $idQuiz = null, $idQuestion = null): Response
    {
        if ($this->isCsrfTokenValid('delete'.$answer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($answer);
            $entityManager->flush();
        }

        if ($idQuestion) {return $this->redirectToRoute('questionAnswer_index', ['idQuiz' => $idQuiz, 'idQuestion' => $idQuestion,]);}
        else {return $this->redirectToRoute('answer_index');}
    }
}
