<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="projekt")
 */
class Projekt extends AbstractBasicEntity
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $projektName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $strasse;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $hausnummer;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $zusatz;

    /**
     * @var string
     *
     * @ORM\Column( type="string", length=255)
     */
    private $postleitzahl;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $ort;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Rolle", mappedBy="projekt", cascade={"remove", "persist"})
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
     * @ORM\OneToOne(targetEntity="User", cascade={"remove", "persist"})
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
     * @return string
     */
    public function getProjektName()
    {
        return $this->projektName;
    }

    /**
     * @param string $projektName
     */
    public function setProjektName($projektName)
    {
        $this->projektName = $projektName;
    }

    /**
     * @return string
     */
    public function getStrasse()
    {
        return $this->strasse;
    }

    /**
     * @param string $strasse
     */
    public function setStrasse($strasse)
    {
        $this->strasse = $strasse;
    }

    /**
     * @return string
     */
    public function getHausnummer()
    {
        return $this->hausnummer;
    }

    /**
     * @param string $hausnummer
     */
    public function setHausnummer($hausnummer)
    {
        $this->hausnummer = $hausnummer;
    }

    /**
     * @return string
     */
    public function getZusatz()
    {
        return $this->zusatz;
    }

    /**
     * @param string $zusatz
     */
    public function setZusatz($zusatz)
    {
        $this->zusatz = $zusatz;
    }

    /**
     * @return string
     */
    public function getPostleitzahl()
    {
        return $this->postleitzahl;
    }

    /**
     * @param string $postleitzahl
     */
    public function setPostleitzahl($postleitzahl)
    {
        $this->postleitzahl = $postleitzahl;
    }

    /**
     * @return string
     */
    public function getOrt()
    {
        return $this->ort;
    }

    /**
     * @param string $ort
     */
    public function setOrt($ort)
    {
        $this->ort = $ort;
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


}