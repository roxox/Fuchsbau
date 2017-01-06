<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="projekt")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjektRepository")
 */
class Projekt extends AbstractBasicEntity
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Rolle", inversedBy="projekte", cascade={"remove", "persist"})
     * @ORM\JoinTable(name="projekte_rollen")
     */
    private $rollen;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="User", inversedBy="projekte", cascade={"remove", "persist"})
     * @ORM\JoinTable(name="projekte_users")
     */
    private $users;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $owner;

    /**
     * string
     *
     * @ORM\Column(type="string", length=255)
     */

    private $einladungscode;

    /**
     * @var Haus
     *
     * @ORM\OneToOne(targetEntity="Haus", inversedBy="projekt", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="haus_id", referencedColumnName="id")
     */
    private $haus;

    /**
     * @var Grundstueck
     *
     * @ORM\OneToOne(targetEntity="Grundstueck", inversedBy="projekt", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="grundstueck_id", referencedColumnName="id")
     */
    private $grundstueck;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $lastOpened = false;

    /**
     * Projekt constructor.
     */
    public function __construct()
    {
        $this->rollen = new ArrayCollection();
        $this->firmen = new ArrayCollection();
        $this->personen = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    /**
     * Funktionen
     */

    /**
     * Ermittlung aller Firmen, die über Rollen an einem Projekt tätig sind.
     *
     * @return Firma[]|array
     */
    public function getFirmen()
    {
        $firmen = [];
        /** @var Rolle $rolle */
        foreach ($this->getRollen() as $rolle) {
            foreach ($rolle->getFirmen() as $firma) {
                if ($firma->isNoCompany() === false) {
                    $firmen[] = $firma;
                }
            }
        }
        return $firmen;

    }

    /**
     * Ermittlung aller Firmen, die über Rollen an einem Projekt tätig sind.
     *
     * @return array|Person[]
     */
    public function getPersonen()
    {
        $personenArr = [];
        /** @var Rolle $rolle */
        foreach ($this->getRollen() as $rolle) {
            /** @var Firma $firma */
            foreach ($rolle->getFirmen() as $firma) {
                foreach ($firma->getPersonen() as $person) {
                    $personenArr[] = $person;
                }
            }
        }
        return array_unique(array_merge([], $personenArr));
    }

    public function getRolleByTypKurzname(string $kurzname)
    {
        $rolleArr = [];
        foreach ($this->getRollen() as $rolle) {
            /** @var Rolle $rolle */
            if ($rolle->getRolletyp()->getKurzname() === $kurzname) {
                $rolleArr[] = $rolle;
            }
        }
        return $rolleArr;
    }

    public function getGesamtKostenByKurzname(string $kurzname)
    {
        $rollen = $this->getRolleByTypKurzname($kurzname);
        $gesamt = 0;
        /** @var Rolle $rolle */
        foreach ($rollen as $rolle) {
            $gesamt = $gesamt + $rolle->getKostenPlan() * $rolle->getAnzahl();
        }
        return $gesamt;
    }

    public function getGesamtKostenInklMwstByKurzname(string $kurzname)
    {
        $rollen = $this->getRolleByTypKurzname($kurzname);
        $gesamt = 0;
        /** @var Rolle $rolle */
        foreach ($rollen as $rolle) {
            $gesamt = $gesamt + $rolle->getKostenInklMwsT($rolle->getKostenPlan()) * $rolle->getAnzahl();
        }
        return $gesamt;
    }

    public function getGesamtKostenExklMwstByKurzname(string $kurzname)
    {
        $rollen = $this->getRolleByTypKurzname($kurzname);
        $gesamt = 0;
        /** @var Rolle $rolle */
        foreach ($rollen as $rolle) {
            $gesamt = $gesamt + $rolle->getKostenExklMwsT($rolle->getKostenPlan()) * $rolle->getAnzahl();
        }
        return $gesamt;
    }

    public function getGrundstueckspreis()
    {
        if ($this->getGrundstueck()) {
            return $this->grundstueck->getGroesse() * ($this->grundstueck->getKaufpreis(
                ) + $this->grundstueck->getErschiessungskostenanteil());
        }

        return '---';
    }

    public function getHauskaufpreis()
    {
        if ($this->getHaus()) {
            return $this->haus->getKaufpreis() +
            $this->getGesamtKostenInklMwstByKurzname('IE') +
            $this->getGesamtKostenInklMwstByKurzname('EE') +
            $this->getGesamtKostenInklMwstByKurzname('T');
        }
        return '---';
    }

    public function getGesamtpreis()
    {
        $gesamtKosten = $this->getHauskaufpreis() +
            $this->getGrundstueckspreis() +
            $this->getGesamtKostenInklMwstByKurzname('N') +
            $this->getGesamtKostenInklMwstByKurzname('A') +
            $this->getGesamtKostenInklMwstByKurzname('I') -
            $this->getGesamtKostenInklMwstByKurzname('G') ;

            return $gesamtKosten ?: '---';
    }

    /**
     * Getter und Setter
     */

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return array|Rolle[]
     */
    public function getRollen()
    {
        return $this->rollen->toArray();
    }

    /**
     * @param Rolle $rolle
     * @return self
     */
    public function addRolle(Rolle $rolle)
    {
        $this->rollen->add($rolle);
        $rolle->setProjekt($this);

        return $this;
    }

    /**
     * @param Rolle $rolle
     * @return bool
     */
    public function hasRolle(Rolle $rolle)
    {
        return $this->rollen->contains($rolle);
    }

    /**
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users->toArray();
    }

    /**
     * @param User $user
     * @return self
     */
    public function addUser(User $user)
    {
        $this->users->add($user);

        if (!$user->hasProjekt($this)) {
            $user->addProjekt($this);
        }

        return $this;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function hasUser(User $user)
    {
        return $this->users->contains($user);
    }

    /**
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return string
     */
    public function getEinladungscode()
    {
        return $this->einladungscode;
    }

    /**
     * @param string $einladungscode
     */
    public function setEinladungscode($einladungscode)
    {
        $this->einladungscode = $einladungscode;
    }

    /**
     * @return Haus
     */
    public function getHaus()
    {
        return $this->haus;
    }

    /**
     * @param Haus $haus
     */
    public function setHaus($haus)
    {
        $this->haus = $haus;
        if (!$haus->getProjekt()) {
            $haus->setProjekt($this);
        }
    }

    /**
     * @return Grundstueck
     */
    public function getGrundstueck()
    {
        return $this->grundstueck;
    }

    /**
     * @param Grundstueck $grundstueck
     */
    public function setGrundstueck($grundstueck)
    {
        $this->grundstueck = $grundstueck;
        if (!$grundstueck->getProjekt()) {
            $grundstueck->setProjekt($this);
        }
    }

    /**
     * @return boolean
     */
    public function isLastOpened()
    {
        return $this->lastOpened;
    }

    /**
     * @param boolean $lastOpened
     */
    public function setLastOpened($lastOpened)
    {
        $this->lastOpened = $lastOpened;
    }


}