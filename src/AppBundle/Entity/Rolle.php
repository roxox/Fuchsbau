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
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Firma", inversedBy="rollen", cascade={"remove", "persist"})
     * @ORM\JoinTable(name="firmen_rollen")
     */
    private $firmen;

    /**
     * @var Projekt
     *
     * @ORM\ManyToOne(targetEntity="Projekt", inversedBy="rollen", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="projekt_id", referencedColumnName="id", onDelete="set null")
     */
    private $projekt;

    /**
     * @var Rolletyp
     * @ORM\ManyToOne(targetEntity="Rolletyp")
     */
    private $rolletyp;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $kosten;

    /**
     * @var Mehrwertsteuer
     * @ORM\ManyToOne(targetEntity="Mehrwertsteuer")
     */
    private $mehrwertsteuer;

    /**
     * Rolle constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->firmen = new ArrayCollection();
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

        if (!$firma->hasRolle($this)) {
            $firma->addRolle($this);
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

    /**
     * @return Rolletyp
     */
    public function getRolletyp()
    {
        return $this->rolletyp;
    }

    /**
     * @param Rolletyp $rolletyp
     */
    public function setRolletyp($rolletyp)
    {
        $this->rolletyp = $rolletyp;
    }

    /**
     * @return Mehrwertsteuer
     */
    public
    function getMehrwertsteuer()
    {
        return $this->mehrwertsteuer;
    }

    /**
     * Set mehrwertsteuer
     *
     * @param Mehrwertsteuer $mehrwertsteuer
     * @return Rolle
     */
    public
    function setMehrwertsteuer(
        Mehrwertsteuer $mehrwertsteuer = null
    ) {
        $this->mehrwertsteuer = $mehrwertsteuer;
        return $this;
    }

    /**
     * @return float
     */
    public function getKosten()
    {
        return $this->kosten;
    }

    /**
     * @param float $kosten
     */
    public function setKosten($kosten)
    {
        $this->kosten = $kosten;
    }

    /**
     * @return float %kostenInklMwst
     */
    public function getKostenInklMwsT()
    {
        return $this->mehrwertsteuer->isInklusive() ? $this->kosten : $this->kosten * (($this->mehrwertsteuer->getWert() / 100) + 1);
    }

    /**
     * @return float %kostenExklMwst
     */
    public function getKostenExklMwsT()
    {
        return $this->mehrwertsteuer->isInklusive() ? $this->kosten / (($this->mehrwertsteuer->getWert() / 100) + 1) : $this->kosten;
    }


}

