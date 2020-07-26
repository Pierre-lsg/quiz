<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Entity\Question;
use App\Form\QuestionType;
use App\Service\FileUploader;
use App\Repository\QuizRepository;
use App\Repository\QuestionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/manage")
 */
class QuestionController extends AbstractController
{
    /**
     * @Route("/questions/", name="question_index", methods={"GET"})
     * @Route("/quiz/{idQuiz}/questions/", name="quizQuestion_index", methods={"GET"})
     */
    public function index(QuestionRepository $questionRepository, $idQuiz = null): Response
    {
        if ($idQuiz)
        {
            /* Récupérer les informations du Quiz */
            $quiz = new Quiz();
            $repo = $this->getDoctrine()->getRepository('App:Quiz');
            $quiz = $repo->find($idQuiz);

            /* Récupérer les questions */
            $questions = $quiz->getQuestions();
            return $this->render('question/index.html.twig', [
                'questions' => $questions,
                'quizId' => $quiz->getId(),
                'quizLabel' => $quiz->getLabel(),
                ]);
        }
        else
        {
            $questions = $questionRepository->findAll();
            return $this->render('question/index.html.twig', [
                'questions' => $questions,
                'quizId' => null,
                'quizLabel' => null,
                ]);
        }
    }

    /**
     * @Route("/question/new", name="question_new", methods={"GET","POST"})
     * @Route("/quiz/{idQuiz}/question/new", name="quizQuestion_new", methods={"GET","POST"})
     */
    public function new(Request $request, $idQuiz = null, QuizRepository $quizRepo, FileUploader $fileUploader): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($idQuiz) {
            $quiz = $quizRepo->find($idQuiz);
            $question->setQuiz($quiz);   
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('picture')->getData();

            if ($pictureFile) {
                $pictureFileName = $fileUploader->upload($pictureFile);
                $quiz->setPicture($pictureFileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            $entityManager->flush();

            if ($idQuiz) {return $this->redirectToRoute('quizQuestion_index', ['idQuiz' => $idQuiz ]);}
            else {return $this->redirectToRoute('question_index');}
        }

        return $this->render('question/new.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
            'quizId' => $idQuiz,
        ]);
    }

    /**
     * @Route("/question/{id}", name="question_show", methods={"GET"})
     * @Route("/quiz/{idQuiz}/question/{id}", name="quizQuestion_show", methods={"GET"})
     */
    public function show(Question $question, $idQuiz = null): Response
    {
        return $this->render('question/show.html.twig', [
            'question' => $question,
            'quizId' => $idQuiz,
        ]);
    }

    /**
     * @Route("/quiz/{idQuiz}/question/{id}/answers", name="quizQuestion_answers", methods={"GET"})
     */
    public function questions(Question $question): Response
    {
        return $this->render('answer/index.html.twig', [
            'answers' => $question->getAnswers(),
        ]);
    }

    /**
     * @Route("/question/{id}/edit", name="question_edit", methods={"GET","POST"})
     * @Route("/quiz/{idQuiz}/question/{id}/edit", name="quizQuestion_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Question $question, $idQuiz = null, QuizRepository $quizRepo, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($idQuiz) {
            $quiz = $quizRepo->find($idQuiz);
            $question->setQuiz($quiz);   
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('picture')->getData();

            if ($pictureFile) {
                $pictureFileName = $fileUploader->upload($pictureFile);
                $quiz->setPicture($pictureFileName);
            }

            $this->getDoctrine()->getManager()->flush();

            if ($idQuiz) {return $this->redirectToRoute('quizQuestion_index', ['idQuiz' => $idQuiz ]);}
            else {return $this->redirectToRoute('question_index');}
        }

        return $this->render('question/edit.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
            'quizId' => $idQuiz,
        ]);
    }

    /**
     * @Route("/question/{id}", name="question_delete", methods={"DELETE"})
     * @Route("/quiz/{idQuiz}/question/{id}", name="quizQuestion_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Question $question, $idQuiz = null): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($question);
            $entityManager->flush();
        }

        if ($idQuiz) {return $this->redirectToRoute('quizQuestion_index', ['idQuiz' => $idQuiz ]);}
        else {return $this->redirectToRoute('question_index');}
    }
}
