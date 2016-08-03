<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Rolle
 *
 * @ORM\Table(name="rolle")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HouseBuilderRepository")
 */
class Rolle extends AbstractBasicEntity
{

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $rollenName;

    /**
     * @var Firma
     *
     * @ORM\ManyToOne(targetEntity="Firma", inversedBy="rollen", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="firma_id", referencedColumnName="id", onDelete="set null")
     */
    private $firma;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Person", mappedBy="rollen")
     */
    private $personen;

    /**
     * @var Projekt
     *
     * @ORM\ManyToOne(targetEntity="Projekt", inversedBy="rollen", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="projekt_id", referencedColumnName="id", onDelete="set null")
     */
    private $projekt;

    /**
     * Address rolle.
     * @param string $rollenName
     */
    public function __construct(string $rollenName)
    {
        $this->rollenName = $rollenName;
        $this->firmen = new ArrayCollection();
        $this->personen = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getPersonen()
    {
        return $this->personen->toArray();
    }

    /**
     * @param Person $person
     * @return self
     */
    public function addPerson(Person $person)
    {
        $this->personen->add($person);

        if (!$person->hasRolle($this)) {
            $person->addRolle($this);
        }

        return $this;
    }

    /**
     * @param Person $person
     * @return bool
     */
    public function hasPerson(Person $person)
    {
        return $this->personen->contains($person);
    }

    /**
     * @return string
     */
    public function getRollenName()
    {
        return $this->rollenName;
    }

    /**
     * @param string $rollenName
     */
    public function setRollenName($rollenName)
    {
        $this->rollenName = $rollenName;
    }

    /**
     * @return Firma
     */
    public function getFirma()
    {
        return $this->firma;
    }

    /**
     * @param Firma $firma
     */
    public function setFirma($firma)
    {
        $this->firma = $firma;
        if (!$firma->hasRolle($this)){
            $firma->addRolle($this);
        }
    }

    /**
     * @return Projekt
     */
    public function getProjekt()
    {
        return $this->projekt;
    }

    /**
     * @param Projekt $projekt
     */
    public function setProjekt($projekt)
    {
        $this->projekt = $projekt;
        if (!$projekt->hasRolle($this)){
            $projekt->addRolle($this);
        }
    }


}

