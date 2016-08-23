<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Email
 *
 * @ORM\Table(name="email")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EmailRepository")
 */
class Email extends AbstractBasicEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="emailadresse", type="string", length=255)
     */
    private $emailadresse;


    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Person", mappedBy="emailadressen")
     */
    private $personen;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Firma", mappedBy="emailadressen")
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
     * Address constructor.
     */
    public function __construct()
    {
        $this->personen = new ArrayCollection();
        $this->firmen = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getEmailadresse()
    {
        return $this->emailadresse;
    }

    /**
     * @param string $emailadresse
     */
    public function setEmailadresse($emailadresse)
    {
        $this->emailadresse = $emailadresse;
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

        if (!$person->hasEmailadresse($this)) {
            $person->addEmailadresse($this);
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

        if (!$firma->hasEmailadresse($this)) {
            $firma->addEmailadresse($this);
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

