<?php

namespace App\Entity;

use App\Repository\PlayerAnswerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlayerAnswerRepository::class)
 */
class PlayerAnswer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Answer::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $answer;

    /**
     * @ORM\ManyToOne(targetEntity=Participation::class, inversedBy="Answers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $participation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnswer(): ?Answer
    {
        return $this->answer;
    }

    public function setAnswer(?Answer $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getParticipation(): ?Participation
    {
        return $this->participation;
    }

    public function setParticipation(?Participation $participation): self
    {
        $this->participation = $participation;

        return $this;
    }
}
