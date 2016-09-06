<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Rolle
 *
 * @ORM\Table(name="rolle")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RolleRepository")
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
    private $kostenPlan;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $kostenIst;

    /**
     * @var Einheit
     * @ORM\ManyToOne(targetEntity="Einheit")
     */
    private $einheit;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $anzahl;

    /**
     * @var Mehrwertsteuer
     * @ORM\ManyToOne(targetEntity="Mehrwertsteuer")
     */
    private $mehrwertsteuer;

    /**
     * @var Rolle
     * @ORM\ManyToOne(targetEntity="Rolle", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @var ArrayCollection|Rolle[]
     *
     * @ORM\OneToMany(targetEntity="Rolle", mappedBy="parent", cascade={"remove", "persist"})
     */
    private $children;

    /**
     * Rolle constructor.
     */
    public function __construct()
    {
        $this->firmen = new ArrayCollection();
        $this->children = new ArrayCollection();
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
        if (!$projekt->hasRolle($this)) {
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
    public function getKostenPlan()
    {
        return $this->kostenPlan;
    }

    /**
     * @param float $kostenPlan
     */
    public function setKostenPlan($kostenPlan)
    {
        $this->kostenPlan = $kostenPlan;
    }

    /**
     * @return float
     */
    public function getKostenIst()
    {
        return $this->kostenIst;
    }

    /**
     * @param float $kostenIst
     */
    public function setKostenIst($kostenIst)
    {
        $this->kostenIst = $kostenIst;
    }

    /**
     * @return float %kostenInklMwst
     */
    public function getKostenInklMwsT($kosten)
    {
        if ($this->mehrwertsteuer && $kosten) {
            return $this->mehrwertsteuer->isInklusive() ?
                $kosten : $kosten * (($this->mehrwertsteuer->getWert() / 100) + 1);
        }
        return 0;
    }

    /**
     * @return float %kostenExklMwst
     */
    public function getKostenExklMwsT($kosten)
    {
        if ($this->mehrwertsteuer && $kosten) {
            return $this->mehrwertsteuer->isInklusive() ?
                $kosten / (($this->mehrwertsteuer->getWert() / 100) + 1) : $kosten;
        }
        return 0;
    }

    public function getGesamtPreis($kosten)
    {
        if ($this->anzahl && $kosten) {
            return $this->anzahl * $kosten;
        }
        return null;
    }

    public function getGesamtIstPreisInklMwstUndUnterpunkten()
    {
        $gesamtPreis = 0;
        $gesamtPreis += $this->getGesamtPreis($this->getKostenInklMwsT($this->getKostenIst()));
        foreach ($this->children as $child) {
            $gesamtPreis += $child->getGesamtPreis($child->getKostenInklMwsT($child->getKostenIst()));
        }
        return $gesamtPreis;
    }

    public function getGesamtPlanPreisInklMwstUndUnterpunkten()
    {
        $gesamtPreis = 0;
        $gesamtPreis += $this->getGesamtPreis($this->getKostenInklMwsT($this->getKostenPlan()));
        foreach ($this->children as $child) {
            $gesamtPreis += $child->getGesamtPreis($child->getKostenInklMwsT($child->getKostenPlan()));
        }
        return $gesamtPreis;
    }

    public function getGesamtIstPreisExklMwstUndUnterpunkten()
    {
        $gesamtPreis = 0;
        $gesamtPreis += $this->getGesamtPreis($this->getKostenExklMwsT($this->getKostenIst()));
        foreach ($this->children as $child) {
            $gesamtPreis += $child->getGesamtPreis($child->getKostenExklMwsT($child->getKostenIst()));
        }
        return $gesamtPreis;
    }

    public function getGesamtPlanPreisExklMwstUndUnterpunkten()
    {
        $gesamtPreis = 0;
        $gesamtPreis += $this->getGesamtPreis($this->getKostenExklMwsT($this->getKostenPlan()));
        foreach ($this->children as $child) {
            $gesamtPreis += $child->getGesamtPreis($child->getKostenExklMwsT($child->getKostenPlan()));
        }
        return $gesamtPreis;
    }

    /**
     * @return Einheit
     */
    public function getEinheit()
    {
        return $this->einheit;
    }

    /**
     * @param Einheit $einheit
     */
    public function setEinheit($einheit)
    {
        $this->einheit = $einheit;
    }

    /**
     * @return float
     */
    public function getAnzahl()
    {
        return $this->anzahl;
    }

    /**
     * @param float $anzahl
     */
    public function setAnzahl($anzahl)
    {
        $this->anzahl = $anzahl;
    }

    /**
     * @return Rolle
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Rolle $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return Rolle[]|ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set feature
     *
     * @param Rolle $child
     * @return Rolle
     */
    public function addChild(Rolle $child)
    {
        $this->children->add($child);
        $child->setParent($this);
        return $this;
    }

    public function hasChildren()
    {
        return count($this->children) != 0 ? true : false;
    }

}

