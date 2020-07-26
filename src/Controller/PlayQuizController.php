<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Participation;
use App\Entity\Player;
use App\Entity\PlayerAnswer;
use App\Entity\Question;
use App\Form\AnswerType;
use App\Repository\QuizRepository;
use App\Repository\AnswerRepository;
use App\Repository\ParticipationRepository;
use App\Repository\PlayerAnswerRepository;
use App\Repository\PlayerRepository;
use App\Repository\QuestionRepository;
use App\Repository\ResultCommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlayQuizController extends AbstractController
{
    /**
     * @Route("/", name="home_page")
     * @Route("/play", name="play")
     * @Route("/play/quiz", name="list_quizzes")
     */
    public function index(QuizRepository $quizRepository)
    {
        /* Liste des quiz */
        $quizzes = $quizRepository->findAll();

        return $this->render('play_quiz/index.html.twig', [
            'quizzes' => $quizzes,
        ]);
    }

    /**
     * @Route("/play/quiz/{idQuiz}", name="intro_quiz", methods={"GET|POST"})
     */
    public function play(QuizRepository $quizRepository, $idQuiz, SessionInterface $session, Request $request, PlayerRepository $playerRepository)
    {
        /* Quiz Info */
        $quiz = $quizRepository->find($idQuiz);

        $questionsGame = [];

        foreach ($quiz->getQuestions() as $question)
        {
            $questionsGame[$question->getId()] = null; 
        }

        $session->set('questionsGame', $questionsGame);

        if (!$quiz->getPicture()) { $quiz->setPicture('default.jpeg'); }


        /* If email submitted */
        if($request->get('email')) {
            /* Create Entity Manager */
            $em = $this->getDoctrine()->getManager();

            /* Save the player informations */
            $player = new Player();
            $player->setMailAdress($request->get('email'))
                   ->setNickname($request->get('nickname'))
            ;

            /* If not exist - save player */
            $em->getRepository(Player::class);
            $playerKnown = $playerRepository->findOneBy(['mailAdress' => $player->getMailAdress()]);

            if ($playerKnown) {
                $playerKnown->setNickname($player->getNickname());
                $player = $playerKnown;
            }
            $em->persist($player);
            $em->flush();
            $session->set('playerId', $player->getId());

            /* Create a participation */
            $participation = new Participation();
            $participation->setPlayer($player)
                          ->setQuiz($quiz)
                          ->setPlayedAt(new \DateTime())
            ;
            $em->getRepository(Participation::class);
            $em->persist($participation);
            $em->flush();
            $session->set('participationId', $participation->getId());

            /* Start the game */
            return $this->redirectToRoute('play_quiz', [
                'idQuiz' => $idQuiz
            ]);

        }

        /*TODO :  All above should be in a function or in a service for reuse */
        return $this->render('play_quiz/intro.html.twig', [
            'quiz' => $quiz,
        ]);
    }

    /**
     * @Route("/play/quiz/{idQuiz}/questions", name="play_quiz", methods={"GET|POST"})
     */
    public function questions($idQuiz, Request $request, QuestionRepository $questionRepository, SessionInterface $session, 
                              PlayerAnswerRepository $playerAnswerRepository, AnswerRepository $answerRepository, ParticipationRepository $participationRepository)
    {
        $questionAsked = new Question();

        $questionsGame = $session->get('questionsGame', []);
        $prevQuestionId = $session->get('questionId'); 

        /* If an answer is given */
        if($request->get('answerId') && $prevQuestionId) {
            /* Register the answer in the session */
            $questionsGame[$prevQuestionId] = $request->get('answerId'); 
            $session->set('questionsGame', $questionsGame);

            /* Register the answer in the DB */
            $em = $this->getDoctrine()->getManager();
            $em->getRepository(PlayerAnswer::class);

            $playerAnswer = new PlayerAnswer();
            $playerAnswer->setAnswer($answerRepository->find($request->get('answerId')))
                         ->setParticipation($participationRepository->find($session->get('participationId')))
            ;

            $em->persist($playerAnswer);
            $em->flush();
        }

        /* Find a question without answer */
        foreach ($questionsGame as $questionId => $answer) {
            $allQuestionAsked = true;
            if ($answer === null) {
                $questionAsked = $questionRepository->find($questionId);
                $session->set('questionId', $questionId);
                $allQuestionAsked = false;
                break;
            }
        }
        
        /* If all questions are completed */
        if ($allQuestionAsked)
        {
            return $this->redirectToRoute('result_quiz', [
                'idQuiz' => $idQuiz
            ]);
        }

        if (!$questionAsked->getPicture()) { $questionAsked->setPicture('default.jpeg'); }

        /*TODO :  All above should be in a function or in a service for reuse */
        return $this->render('play_quiz/play.html.twig', [
            'question' => $questionAsked,
            'answers' => $questionAsked->getAnswers(),
            'quizId' => $idQuiz,
        ]);
    }

    /**
     * @Route("/play/quiz/{idQuiz}/result", name="result_quiz", methods={"GET|POST"})
     */
    public function result(QuizRepository $quizRepository, $idQuiz, SessionInterface $session, AnswerRepository $answerRepository, 
                           ResultCommentRepository $resultCommentRepository, ParticipationRepository $participationRepository)
    {
        $quiz = $quizRepository->find($idQuiz);
        $questionsGame = $session->get('questionsGame', []);

        if (!$quiz->getPicture()) { $quiz->setPicture('default.jpeg'); }

        /* Score */
        $score = 0;
        foreach($questionsGame as $questionId => $answerId)
        {
            $score += $answerRepository->find($answerId)->getValue();
        }

        /* Max Score */
        $maxScore = $quiz->getQuestions()->count();

        /* Result Comment */
        $comment = $resultCommentRepository->findOneByQuizAndScore($quiz, ($score / $maxScore) * 20); 

        /* Save the participation's result : update */
        $em = $this->getDoctrine()->getManager();
        $em->getRepository(Participation::class);

        $participation = $participationRepository->find($session->get('participationId'));

        $participation->setResult($score . '/' . $maxScore);
        $em->persist($participation);
        $em->flush();

        /*TODO :  All above should be in a function or in a service for reuse */
        return $this->render('play_quiz/outtro.html.twig', [
            'quiz' => $quiz,
            'score' => $score,
            'maxScore' => $maxScore,
            'comment' => $comment->getComment(),
        ]);
    }
}
