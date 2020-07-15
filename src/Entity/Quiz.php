<?php

namespace App\Entity;

use App\Repository\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuizRepository::class)
 */
class Quiz
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="quiz", orphanRemoval=true)
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity=ResultComment::class, mappedBy="quiz", orphanRemoval=true)
     */
    private $resultComments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $creator;

    /**
     * @ORM\OneToMany(targetEntity=Participation::class, mappedBy="quiz")
     */
    private $participations;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->resultComments = new ArrayCollection();
        $this->participations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setQuiz($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getQuiz() === $this) {
                $question->setQuiz(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ResultComment[]
     */
    public function getResultComments(): Collection
    {
        return $this->resultComments;
    }

    public function addResultComment(ResultComment $resultComment): self
    {
        if (!$this->resultComments->contains($resultComment)) {
            $this->resultComments[] = $resultComment;
            $resultComment->setQuiz($this);
        }

        return $this;
    }

    public function removeResultComment(ResultComment $resultComment): self
    {
        if ($this->resultComments->contains($resultComment)) {
            $this->resultComments->removeElement($resultComment);
            // set the owning side to null (unless already changed)
            if ($resultComment->getQuiz() === $this) {
                $resultComment->setQuiz(null);
            }
        }

        return $this;
    }

    public function getCreator(): ?string
    {
        return $this->creator;
    }

    public function setCreator(?string $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function __toString()
    {
        return $this->getLabel();
    }

    /**
     * @return Collection|Participation[]
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Participation $participation): self
    {
        if (!$this->participations->contains($participation)) {
            $this->participations[] = $participation;
            $participation->setQuiz($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): self
    {
        if ($this->participations->contains($participation)) {
            $this->participations->removeElement($participation);
            // set the owning side to null (unless already changed)
            if ($participation->getQuiz() === $this) {
                $participation->setQuiz(null);
            }
        }

        return $this;
    }
}
