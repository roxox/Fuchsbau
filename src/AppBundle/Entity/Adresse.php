<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Adresse
 *
 * @ORM\Table(name="adresse")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AdresseRepository")
 */
class Adresse extends AbstractBasicEntity
{
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
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $hauptadresse = false;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Person", mappedBy="adressen")
     */
    private $personen;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Firma", mappedBy="adressen")
     */
    private $firmen;

    /**
     * @var PrivatGeschaeft
     *
     * @ORM\ManyToOne(targetEntity="PrivatGeschaeft")
     * @ORM\JoinColumn(name="privat_geschaeft_id", referencedColumnName="id", onDelete="set null")
     */
    private $privatGeschaeft;

    /**
     * AbstractBasicAdresse constructor.
     */
    public function __construct()
    {
        $this->personen = new ArrayCollection();
        $this->firmen = new ArrayCollection();
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
     * @return boolean
     */
    public function isHauptadresse()
    {
        return $this->hauptadresse;
    }

    /**
     * @param boolean $hauptadresse
     */
    public function setHauptadresse($hauptadresse)
    {
        $this->hauptadresse = $hauptadresse;
    }

    /**
     * @return array|Person[]
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

        if (!$person->hasAdresse($this)) {
            $person->addAdresse($this);
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
     * @return array|Firma[]
     */
    public function getFirmen()
    {
        return $this->firmen->toArray();
    }

    /**
     * @param Firma $firma
     * @return self
     */
    public function addFirma(Firma $firma)
    {
        $this->firmen->add($firma);

        if (!$firma->hasAdresse($this)) {
            $firma->addAdresse($this);
        }

        return $this;
    }

    /**
     * @param Firma $firma
     * @return bool
     */
    public function hasFirma(Firma $firma)
    {
        return $this->firmen->contains($firma);
    }

    /**
     * @return PrivatGeschaeft
     */
    public function getPrivatGeschaeft()
    {
        return $this->privatGeschaeft;
    }

    /**
     * @param PrivatGeschaeft $privatGeschaeft
     */
    public function setPrivatGeschaeft($privatGeschaeft)
    {
        $this->privatGeschaeft = $privatGeschaeft;
    }


}

