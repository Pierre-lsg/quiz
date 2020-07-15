<?php

namespace App\Entity;

use App\Repository\ParticipationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParticipationRepository::class)
 */
class Participation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Player::class, inversedBy="participations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $player;

    /**
     * @ORM\ManyToOne(targetEntity=Quiz::class, inversedBy="participations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quiz;

    /**
     * @ORM\Column(type="datetime")
     */
    private $playedAt;

    /**
     * @ORM\OneToMany(targetEntity=PlayerAnswer::class, mappedBy="participation", orphanRemoval=true)
     */
    private $Answers;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $result;

    public function __construct()
    {
        $this->Answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

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

    public function getPlayedAt(): ?\DateTimeInterface
    {
        return $this->playedAt;
    }

    public function setPlayedAt(\DateTimeInterface $playedAt): self
    {
        $this->playedAt = $playedAt;

        return $this;
    }

    /**
     * @return Collection|PlayerAnswer[]
     */
    public function getAnswers(): Collection
    {
        return $this->Answers;
    }

    public function addAnswer(PlayerAnswer $answer): self
    {
        if (!$this->Answers->contains($answer)) {
            $this->Answers[] = $answer;
            $answer->setParticipation($this);
        }

        return $this;
    }

    public function removeAnswer(PlayerAnswer $answer): self
    {
        if ($this->Answers->contains($answer)) {
            $this->Answers->removeElement($answer);
            // set the owning side to null (unless already changed)
            if ($answer->getParticipation() === $this) {
                $answer->setParticipation(null);
            }
        }

        return $this;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(?string $result): self
    {
        $this->result = $result;

        return $this;
    }
}
