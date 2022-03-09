<?php
namespace App\data;

use App\Entity\Site;
use App\Entity\User;
use App\Entity\State;
use phpDocumentor\Reflection\Types\Collection;



class MyFilterCustom {


    private $site;
    private $name;
    private $dateEntre;
    private $dateEt;
    // private $participateEvent;
    // private $notParticipateEvent;
    private $organizer;
    private $statePast;

    
    public function __construct()
    {

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

    public function getDateEntre(): ?\DateTimeInterface
    {
        return $this->dateEntre;
    }

    public function setDateEntre(\DateTimeInterface $dateEntre): self
    {
        $this->dateEntre = $dateEntre;

        return $this;
    }


    public function getDateEt(): ?\DateTimeInterface
    {
        return $this->dateEt;
    }

    public function setDateEt(\DateTimeInterface $dateEt): self
    {
        $this->dateEt = $dateEt;

        return $this;
    }

    public function getStatePast(): ?State
    {
        return $this->statePast;
    }

    public function setStatePast(?State $statePast): self
    {
        $this->statePast = $statePast;

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

    
    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(Site $site): self
    {
        $this->site = $site;

        return $this;
    }


}