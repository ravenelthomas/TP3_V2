<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?Session $id_session = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $done = null;

    #[ORM\Column(length: 255, nullable: true)]
  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdSession(): ?Session
    {
        return $this->id_session;
    }

    public function setIdSession(?Session $id_session): static
    {
        $this->id_session = $id_session;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
    public function setDone(bool $done): static
    {
        $this->done = $done;
        
        return $this;
    }

    public function getDone(): ?string
    {
        return $this->done;
    }
}
