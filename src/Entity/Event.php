<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[Assert\GreaterThanOrEqual('today', message: 'La date doit être supérieur ou égale à celle du jour')]
    #[ORM\Column(type: 'datetime')]
    private $beginAt;

    #[Assert\GreaterThanOrEqual('today', message: 'La date doit être supérieur ou égale à celle du jour')]
    #[ORM\Column(type: 'datetime')]
    private $limitInscriptionAt;


    #[ORM\Column(type: 'integer')]
    private $duration;

    private $isInEvent;

    #[ORM\Column(type: 'integer')]
    private $inscriptionMax;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $photo;

    #[ORM\ManyToOne(targetEntity: State::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private $state;

    #[ORM\Column(type: 'integer')]
    private $isDisplay;

    #[ORM\Column(type: 'boolean')]
    private $isActive;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'events')]
    private $location;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'events')]
    private $participants;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'whiteListedEvents')]
    private $whiteList;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'organizedEvents')]
    #[ORM\JoinColumn(nullable: false)]
    private $owner;

    #[ORM\Column(type: 'boolean')]
    private $privateEvent;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->whiteList = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
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

    public function getLimitInscriptionAt(): ?\DateTimeInterface
    {
        return $this->limitInscriptionAt;
    }

    public function setLimitInscriptionAt(\DateTimeInterface $limitInscriptionAt): self
    {
        $this->limitInscriptionAt = $limitInscriptionAt;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getInscriptionMax(): ?int
    {
        return $this->inscriptionMax;
    }

    public function setInscriptionMax(int $inscriptionMax): self
    {
        $this->inscriptionMax = $inscriptionMax;

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
            $participant->addEvent($this);
            $this->isInEvent = true;
        }

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            $participant->removeEvent($this);
            $this->isInEvent = false;
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getWhiteList(): Collection
    {
        return $this->whiteList;
    }

    public function addWhiteList(User $whiteList): self
    {
        if (!$this->whiteList->contains($whiteList)) {
            $this->whiteList[] = $whiteList;
        }

        return $this;
    }

    public function removeWhiteList(User $whiteList): self
    {
        $this->whiteList->removeElement($whiteList);

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
    public function participantExistInEvent(?User $user): bool
    {
        foreach($this->getParticipants() as $participant){
            if($participant->getId() == $user->getId()){
                $this->isInEvent = true;
                return true;
            }
        }
        $this->isInEvent = false;
        return false;
    }

    public function isInEvent() : bool{
        return $this->isInEvent == null ? false : $this->isInEvent;
    }
   public function setisInEvent(?User $user) : bool{
        $this->isInEvent =  $this->participantExistInEvent($user);

        return (bool)$this;
   }

   public function getPrivateEvent(): ?bool
   {
       return $this->privateEvent;
   }

   public function setPrivateEvent(bool $privateEvent): self
   {
       $this->privateEvent = $privateEvent;

       return $this;
   }

}
