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
    private $name;

    #[ORM\Column(type: 'datetime')]
    private $beginAt;

    #[ORM\Column(type: 'datetime')]
    private $endAt;

    #[ORM\Column(type: 'integer')]
    private $nbOfDaysBeforeClosing;

    #[ORM\Column(type: 'integer')]
    private $registrationMax;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $photo;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private $location;

    #[ORM\ManyToOne(targetEntity: State::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private $state;

    #[ORM\Column(type: 'integer')]
    private $isDisplay;

    #[ORM\Column(type: 'boolean')]
    private $isActive;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private $organizer;


    #[ORM\ManyToMany(targetEntity: Group::class, mappedBy: 'event')]
    private $groups;

    #[ORM\ManyToOne(targetEntity: Site::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private $site;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'participant')]
    private $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->groups = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getname(): ?string
    {
        return $this->name;
    }

    public function setname(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBeginAt(): ?\DateTimeInterface
    {
        return $this->beginAt;
    }

    public function setBeginAt(\DateTimeInterface $beginAt): self
    {
        $this->beginAt = $beginAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getNbOfDaysBeforeClosing(): ?int
    {
        return $this->nbOfDaysBeforeClosing;
    }

    public function setNbOfDaysBeforeClosing(int $nbOfDaysBeforeClosing): self
    {
        $this->nbOfDaysBeforeClosing = $nbOfDaysBeforeClosing;

        return $this;
    }

    public function getRegistrationMax(): ?int
    {
        return $this->registrationMax;
    }

    public function setRegistrationMax(int $registrationMax): self
    {
        $this->registrationMax = $registrationMax;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getIsDisplay(): ?int
    {
        return $this->isDisplay;
    }

    public function setIsDisplay(int $isDisplay): self
    {
        $this->isDisplay = $isDisplay;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getOrganizer(): ?User
    {
        return $this->organizer;
    }

    public function setOrganizer(?User $organizer): self
    {
        $this->organizer = $organizer;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        $this->participants->removeElement($participant);

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
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

}
