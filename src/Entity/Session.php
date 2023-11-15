<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'sessions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $id_user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $start_session = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $end_session = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentary = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $response = null;

    #[ORM\OneToMany(mappedBy: 'id_session', targetEntity: Task::class)]
    private Collection $tasks;

    #[ORM\Column(nullable: true)]
    private ?bool $inSession = null;

    #[ORM\Column(nullable: true)]
    private ?bool $completed = null;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }
    public function __toString(): string
    {
        return $this->getIdUser()->getName() . ' ' . $this->getIdUser()->getSurname() . ' ' . $this->getStartSession()->format('Y-m-d H:i:s');
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getStartSession(): ?\DateTimeInterface
    {
        return $this->start_session;
    }

    public function setStartSession(\DateTimeInterface $start_session): static
    {
        $this->start_session = $start_session;

        return $this;
    }

    public function getEndSession(): ?\DateTimeInterface
    {
        return $this->end_session;
    }

    public function setEndSession(\DateTimeInterface $end_session): static
    {
        $this->end_session = $end_session;

        return $this;
    }

    public function getCommentary(): ?string
    {
        return $this->commentary;
    }

    public function setCommentary(?string $commentary): static
    {
        $this->commentary = $commentary;

        return $this;
    }

    public function getResponse(): ?string
    {
        return $this->response;
    }

    public function setResponse(?string $response): static
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setIdSession($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getIdSession() === $this) {
                $task->setIdSession(null);
            }
        }

        return $this;
    }

    public function changeStartTime(\DateTimeInterface $start_session): static
    {
        $this->start_session = $start_session;

        return $this;
    }

    public function changeEndTime(\DateTimeInterface $end_session): static
    {
        $this->end_session = $end_session;

        return $this;
    }

    public function isInSession(): ?bool
    {
        return $this->inSession;
    }

    public function setInSession(?bool $inSession): static
    {
        $this->inSession = $inSession;

        return $this;
    }

    public function isCompleted(): ?bool
    {
        return $this->completed;
    }

    public function setCompleted(?bool $completed): static
    {
        $this->completed = $completed;

        return $this;
    }
    
}
