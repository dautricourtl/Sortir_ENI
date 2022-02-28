<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Nom;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'groups')]
    private $Membres;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'groups')]
    #[ORM\JoinColumn(nullable: false)]
    private $Createur;

    #[ORM\ManyToMany(targetEntity: Event::class, inversedBy: 'groups')]
    private $Event;

    public function __construct()
    {
        $this->Membres = new ArrayCollection();
        $this->Event = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getMembres(): Collection
    {
        return $this->Membres;
    }

    public function addMembre(User $membre): self
    {
        if (!$this->Membres->contains($membre)) {
            $this->Membres[] = $membre;
        }

        return $this;
    }

    public function removeMembre(User $membre): self
    {
        $this->Membres->removeElement($membre);

        return $this;
    }

    public function getCreateur(): ?User
    {
        return $this->Createur;
    }

    public function setCreateur(?User $Createur): self
    {
        $this->Createur = $Createur;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvent(): Collection
    {
        return $this->Event;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->Event->contains($event)) {
            $this->Event[] = $event;
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        $this->Event->removeElement($event);

        return $this;
    }
}
