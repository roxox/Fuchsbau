<?php

namespace AppBundle\PlainClasses;

use AppBundle\Entity\Firma;
use AppBundle\Entity\Person;
use AppBundle\Entity\PrivatGeschaeft;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Email
 *
 */
class EmailCompany
{
    /**
     * @var string
     *
     */
    private $emailadresse;

    /**
     * @var string
     *
     */
    private $porno;


    /**
     * @var ArrayCollection
     *
     */
    private $personen;

    /**
     * @var ArrayCollection
     *
     */
    private $firmen;

    /**
     * @var PrivatGeschaeft
     *
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

//    /**
//     * @param Person $person
//     * @return self
//     */
//    public function addPerson(Person $person)
//    {
//        $this->personen->add($person);
//
//        if (!$person->hasEmailadresse($this)) {
//            $person->addEmailadresse($this);
//        }
//
//        return $this;
//    }

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
//
//    /**
//     * @param Firma $firma
//     * @return self
//     */
//    public function addFirma(Firma $firma)
//    {
//        $this->firmen->add($firma);
//
//        if (!$firma->hasEmailadresse($this)) {
//            $firma->addEmailadresse($this);
//        }
//
//        return $this;
//    }

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
     * @return string
     */
    public function getPorno()
    {
        return $this->porno;
    }

    /**
     * @param string $porno
     */
    public function setPorno($porno)
    {
        $this->porno = $porno;
    }


}

