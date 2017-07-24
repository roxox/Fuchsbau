<?php

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Builder
 *
 * @ORM\Table(name="person")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PersonRepository")
 */
class Person extends AbstractBasicEntity
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $vorname;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nachname;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="User", inversedBy="person", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Adresse", inversedBy="personen", cascade={"remove", "persist"})
     * @ORM\JoinTable(name="adressen_personen")
     */
    private $adressen;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Email", inversedBy="personen", cascade={"remove", "persist"})
     * @ORM\JoinTable(name="email_personen")
     */
    private $emailadressen;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Telefonnummer", inversedBy="personen", cascade={"remove", "persist"})
     * @ORM\JoinTable(name="telefonnummern_personen")
     */
    private $telefonnummern;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Firma", inversedBy="personen", cascade={"remove", "persist"})
     * @ORM\JoinTable(name="firmen_personen")
     */
    private $firmen;

    /**
     * @var Geschlecht
     *
     * @ORM\ManyToOne(targetEntity="Geschlecht")
     * @ORM\JoinColumn(name="geschlecht_id", referencedColumnName="id", onDelete="set null")
     */
    private $geschlecht;

    /**
     * @var PersonenTitel
     *
     * @ORM\ManyToOne(targetEntity="PersonenTitel")
     * @ORM\JoinColumn(name="personen_titel_id", referencedColumnName="id", onDelete="set null", nullable=true)
     */
    private $titel;

    /**
     * Address constructor.
     * @param User $user
     * @internal param string $nachname
     */
    public function __construct(User $user = null)
    {
        $this->user = $user;
        $this->adressen = new ArrayCollection();
        $this->emailadressen = new ArrayCollection();
        $this->telefonnummern = new ArrayCollection();
        $this->firmen = new ArrayCollection();
    }


    ##############
    # Funktionen #
    ##############

    /**
     * @param Projekt $projekt
     * @return Rolle[]|array
     */
    public function getPersonenRollenByProjekt(Projekt $projekt){
        $rolleArr = [];
        /** @var Firma $noCompany */
        foreach ($this->firmen as $noCompany) {
            if ($noCompany->isNoCompany()) {
                foreach ($noCompany->getRollen() as $rolle) {
                    if ($rolle->getProjekt() === $projekt) {
                        $rolleArr[] = $rolle;
                    }
                }
            }
        }
        return $rolleArr;
    }

//    public function getAlleRollenByProjekt(Projekt $projekt){
//        $rolleArr = [];
//        foreach ($this->firmen as $firma) {
//            /** @var Firma $firma */
//            foreach ($firma->getRollen() as $rolle) {
//                $rolleArr[] = $rolle;
//            }
//        }
//        return array_unique(array_merge([], ...$rolleArr));
//    }

    ###########################
    # Getter / Setter / Adder #
    ###########################

    /**
     * @return string
     */
    public function getVorname()
    {
        return $this->vorname;
    }

    /**
     * @param string $vorname
     */
    public function setVorname($vorname)
    {
        $this->vorname = $vorname;
    }

    /**
     * @return string
     */
    public function getNachname()
    {
        return $this->nachname;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->vorname . ' ' . $this->getNachname();
    }

    /**
     * @param string $nachname
     */
    public function setNachname($nachname)
    {
        $this->nachname = $nachname;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
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

        if (!$adresse->hasPerson($this)) {
            $adresse->addPerson($this);
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

        if (!$telefonnummer->hasPerson($this)) {
            $telefonnummer->addPerson($this);
        }

        return $this;
    }

    /**
     * @param Telefonnummer $telefonnummer
     * @return self
     */
    public function removeTelefonnummer(Telefonnummer $telefonnummer)
    {
//        if ($telefonnummer->hasPerson($this)) {
//            $telefonnummer->removePerson($this);
//        }
        $this->telefonnummern->remove($telefonnummer);


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

        if (!$emailadresse->hasPerson($this)) {
            $emailadresse->addPerson($this);
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

        if (!$firma->hasPerson($this)) {
            $firma->addPerson($this);
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
     * @return Geschlecht
     */
    public function getGeschlecht()
    {
        return $this->geschlecht;
    }

    /**
     * @param Geschlecht $geschlecht
     */
    public function setGeschlecht($geschlecht)
    {
        $this->geschlecht = $geschlecht;
    }

    /**
     * @return PersonenTitel
     */
    public function getTitel()
    {
        return $this->titel;
    }

    /**
     * @param PersonenTitel $titel
     */
    public function setTitel($titel)
    {
        $this->titel = $titel;
    }

}

