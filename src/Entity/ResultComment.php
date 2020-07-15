<?php

namespace App\Entity;

use App\Repository\ResultCommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResultCommentRepository::class)
 */
class ResultComment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $lowerBound;

    /**
     * @ORM\Column(type="integer")
     */
    private $upperBound;

    /**
     * @ORM\Column(type="text")
     */
    private $comment;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reward;

    /**
     * @ORM\ManyToOne(targetEntity=Quiz::class, inversedBy="resultComments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quiz;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLowerBound(): ?int
    {
        return $this->lowerBound;
    }

    public function setLowerBound(int $lowerBound): self
    {
        $this->lowerBound = $lowerBound;

        return $this;
    }

    public function getUpperBound(): ?int
    {
        return $this->upperBound;
    }

    public function setUpperBound(int $upperBound): self
    {
        $this->upperBound = $upperBound;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getReward(): ?string
    {
        return $this->reward;
    }

    public function setReward(?string $reward): self
    {
        $this->reward = $reward;

        return $this;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): self
    {
        $this->quiz = $quiz;

        return $this;
    }
}
