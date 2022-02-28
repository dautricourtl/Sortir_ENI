<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Nom;

    #[ORM\Column(type: 'datetime')]
    private $DebutDate;

    #[ORM\Column(type: 'datetime')]
    private $FinDate;

    #[ORM\Column(type: 'integer')]
    private $NbJoursAvantCloture;

    #[ORM\Column(type: 'integer')]
    private $InscriptionMax;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $Description;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $Photo;

    #[ORM\ManyToOne(targetEntity: Lieu::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private $Lieu;

    #[ORM\ManyToOne(targetEntity: Etat::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private $Etat;

    #[ORM\Column(type: 'integer')]
    private $Visibilite;

    #[ORM\Column(type: 'boolean')]
    private $Actif;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private $Organisateur;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'events')]
    private $Participants;

    #[ORM\ManyToMany(targetEntity: Group::class, mappedBy: 'Event')]
    private $groups;

    #[ORM\ManyToOne(targetEntity: Site::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private $Site;

    public function __construct()
    {
        $this->Participants = new ArrayCollection();
        $this->groups = new ArrayCollection();
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

    public function getDebutDate(): ?\DateTimeInterface
    {
        return $this->DebutDate;
    }

    public function setDebutDate(\DateTimeInterface $DebutDate): self
    {
        $this->DebutDate = $DebutDate;

        return $this;
    }

    public function getFinDate(): ?\DateTimeInterface
    {
        return $this->FinDate;
    }

    public function setFinDate(\DateTimeInterface $FinDate): self
    {
        $this->FinDate = $FinDate;

        return $this;
    }

    public function getNbJoursAvantCloture(): ?int
    {
        return $this->NbJoursAvantCloture;
    }

    public function setNbJoursAvantCloture(int $NbJoursAvantCloture): self
    {
        $this->NbJoursAvantCloture = $NbJoursAvantCloture;

        return $this;
    }

    public function getInscriptionMax(): ?int
    {
        return $this->InscriptionMax;
    }

    public function setInscriptionMax(int $InscriptionMax): self
    {
        $this->InscriptionMax = $InscriptionMax;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->Photo;
    }

    public function setPhoto(?string $Photo): self
    {
        $this->Photo = $Photo;

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->Lieu;
    }

    public function setLieu(?Lieu $Lieu): self
    {
        $this->Lieu = $Lieu;

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->Etat;
    }

    public function setEtat(?Etat $Etat): self
    {
        $this->Etat = $Etat;

        return $this;
    }

    public function getVisibilite(): ?int
    {
        return $this->Visibilite;
    }

    public function setVisibilite(int $Visibilite): self
    {
        $this->Visibilite = $Visibilite;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->Actif;
    }

    public function setActif(bool $Actif): self
    {
        $this->Actif = $Actif;

        return $this;
    }

    public function getOrganisateur(): ?User
    {
        return $this->Organisateur;
    }

    public function setOrganisateur(?User $Organisateur): self
    {
        $this->Organisateur = $Organisateur;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipants(): Collection
    {
        return $this->Participants;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->Participants->contains($participant)) {
            $this->Participants[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        $this->Participants->removeElement($participant);

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->addEvent($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->removeElement($group)) {
            $group->removeEvent($this);
        }

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->Site;
    }

    public function setSite(?Site $Site): self
    {
        $this->Site = $Site;

        return $this;
    }

}
