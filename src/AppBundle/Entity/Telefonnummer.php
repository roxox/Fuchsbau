<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Telefonnummers
 *
 * @ORM\Table(name="telefonnummer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TelefonnummerRepository")
 */
class Telefonnummer extends AbstractBasicEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="vorwahl", type="string", length=255)
     */
    private $vorwahl;

    /**
     * @var string
     *
     * @ORM\Column(name="telefonnummer", type="string", length=255)
     */
    private $telefonnummer;

    /**
     * @var string
     *
     * @ORM\Column(name="durchwahl", type="string", length=255, nullable=true)
     */
    private $durchwahl;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Person", mappedBy="telefonnummern")
     */
    private $personen;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Firma", mappedBy="telefonnummern")
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
     * @var TelefonTyp
     *
     * @ORM\ManyToOne(targetEntity="TelefonTyp")
     * @ORM\JoinColumn(name="telefon_typ_id", referencedColumnName="id", onDelete="set null")
     */
    private $telefonTyp;

    /**
     * Address constructor.
     */
    public function __construct()
    {
        $this->personen = new ArrayCollection();
        $this->firmen = new ArrayCollection();
    }

    /**
     * Set areaCode
     *
     * @param string $vorwahl
     *
     * @return Telefonnummer
     */
    public function setVorwahl($vorwahl)
    {
        $this->vorwahl = $vorwahl;

        return $this;
    }

    /**
     * Get areaCode
     *
     * @return string
     */
    public function getVorwahl()
    {
        return $this->vorwahl;
    }

    /**
     * Set telefonnummer
     *
     * @param string $telefonnummer
     *
     * @return Telefonnummer
     */
    public function setTelefonnummer($telefonnummer)
    {
        $this->telefonnummer = $telefonnummer;

        return $this;
    }

    /**
     * Get telefonnummer
     *
     * @return string
     */
    public function getTelefonnummer()
    {
        return $this->telefonnummer;
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

        if (!$person->hasTelefonnummer($this)) {
            $person->addTelefonnummer($this);
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
     * @param Person $person
     * @return Telefonnummer
     */
    public function removePerson(Person $person)
    {
        $this->personen->remove($person);


        return $this;
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

        if (!$firma->hasTelefonnummer($this)) {
            $firma->addTelefonnummer($this);
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

    /**
     * @return TelefonTyp
     */
    public function getTelefonTyp()
    {
        return $this->telefonTyp;
    }

    /**
     * @param TelefonTyp $telefonTyp
     */
    public function setTelefonTyp($telefonTyp)
    {
        $this->telefonTyp = $telefonTyp;
    }

    /**
     * @return string
     */
    public function getDurchwahl()
    {
        return $this->durchwahl;
    }

    /**
     * @param string $durchwahl
     */
    public function setDurchwahl($durchwahl)
    {
        $this->durchwahl = $durchwahl;
    }

}

