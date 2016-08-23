<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Builder
 *
 * @ORM\Table(name="firma")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FirmaRepository")
 */
class Firma extends AbstractBasicEntity

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
     * @ORM\ManyToMany(targetEntity="Rolle", inversedBy="firmen")
     */
    private $rollen;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Adresse", inversedBy="firmen")
     */
    private $adressen;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Email", inversedBy="firmen", cascade={"remove", "persist"})
     * @ORM\JoinTable(name="email_firmen")
     */
    private $emailadressen;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Telefonnummer", inversedBy="firmen")
     * @ORM\JoinTable(name="telefonnummern_firmen")
     */
    private $telefonnummern;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Person", mappedBy="firmen")
     */
    private $personen;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $noCompany = false;

    /**
     * Address constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->rollen = new ArrayCollection();
        $this->adressen = new ArrayCollection();
        $this->telefonnummern = new ArrayCollection();
        $this->emailadressen = new ArrayCollection();
        $this->personen = new ArrayCollection();
    }

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

        if (!$person->hasFirma($this)) {
            $person->addFirma($this);
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

        if (!$rolle->hasFirma($this)) {
            $rolle->addFirma($this);
        }

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
     * @return array|Adresse[]
     */
    public function getAdressen()
    {
        return $this->adressen->toArray();
    }

    /**
     * @param Adresse $adresse
     * @return self
     */
    public function addAdresse(Adresse $adresse)
    {
        $this->adressen->add($adresse);

        if (!$adresse->hasFirma($this)) {
            $adresse->addFirma($this);
        }

        return $this;
    }

    /**
     * @param Adresse $adresse
     * @return bool
     */
    public function hasAdresse(Adresse $adresse)
    {
        return $this->adressen->contains($adresse);
    }

    /**
     * @return array|Telefonnummer[]
     */
    public function getTelefonnummern()
    {
        return $this->telefonnummern->toArray();
    }

    /**
     * @param Telefonnummer $telefonnummer
     * @return self
     */
    public function addTelefonnummer(Telefonnummer $telefonnummer)
    {
        $this->telefonnummern->add($telefonnummer);

        if (!$telefonnummer->hasFirma($this)) {
            $telefonnummer->addFirma($this);
        }

        return $this;
    }

    /**
     * @param Telefonnummer $telefonnummer
     * @return bool
     */
    public function hasTelefonnummer(Telefonnummer $telefonnummer)
    {
        return $this->telefonnummern->contains($telefonnummer);
    }

    /**
     * @return array|Email[]
     */
    public function getEmailadressen()
    {
        return $this->emailadressen->toArray();
    }

    /**
     * @param Email $emailadresse
     * @return self
     */
    public function addEmailadresse(Email $emailadresse)
    {
        $this->emailadressen->add($emailadresse);

        if (!$emailadresse->hasFirma($this)) {
            $emailadresse->addFirma($this);
        }

        return $this;
    }

    /**
     * @param Email $emailadresse
     * @return bool
     */
    public function hasEmailadresse(Email $emailadresse)
    {
        return $this->emailadressen->contains($emailadresse);
    }

    /**
     * @return boolean
     */
    public function isNoCompany()
    {
        return $this->noCompany;
    }

    /**
     * @param boolean $noCompany
     */
    public function setNoCompany($noCompany)
    {
        $this->noCompany = $noCompany;
    }


}

