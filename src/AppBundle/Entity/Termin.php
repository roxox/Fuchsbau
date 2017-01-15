<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TerminRepository")
 */
class Termin  extends AbstractBasicEntity
{

    /**
     * var string
     * @ORM\Column(type="string")
     */
    private $bezeichnung;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datumStartIst;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datumEndeIst;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datumStartPlan;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datumEndePlan;

    /**
     * @var Rolle
     * @ORM\ManyToOne(targetEntity="Rolle", inversedBy="termine")
     * @ORM\JoinColumn(name="rolle_id", referencedColumnName="id")
     */
    private $rolle;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $planIst = false;

    /**
     * Termin constructor.
     */
    public function __construct($plan = false)
    {
        $this->planIst = $plan;
    }

    /**
     * @return string
     */
    public function getBezeichnung()
    {
        return $this->bezeichnung;
    }

    /**
     * @param string $name
     */
    public function setBezeichnung($bezeichnung)
    {
        $this->bezeichnung = $bezeichnung;
    }

    /**
     * @return mixed
     */
    public function getDatumStartIst()
    {
        return $this->datumStartIst;
    }

    /**
     * @param mixed $datumStartIst
     */
    public function setDatumStartIst($datumStartIst)
    {
        $this->datumStartIst = $datumStartIst;
    }

    /**
     * @return mixed
     */
    public function getDatumEndeIst()
    {
        return $this->datumEndeIst;
    }

    /**
     * @param mixed $datumEndeIst
     */
    public function setDatumEndeIst($datumEndeIst)
    {
        $this->datumEndeIst = $datumEndeIst;
    }

    /**
     * @return mixed
     */
    public function getDatumStartPlan()
    {
        return $this->datumStartPlan;
    }

    /**
     * @param mixed $datumStartPlan
     */
    public function setDatumStartPlan($datumStartPlan)
    {
        $this->datumStartPlan = $datumStartPlan;
    }

    /**
     * @return mixed
     */
    public function getDatumEndePlan()
    {
        return $this->datumEndePlan;
    }

    /**
     * @param mixed $datumEndeIst
     */
    public function setDatumEndePlan($datumEndePlan)
    {
        $this->datumEndePlan = $datumEndePlan;
    }

    /**
     * @return Rolle
     */
    public function getRolle(): Rolle
    {
        return $this->rolle;
    }

    /**
     * @param Rolle $rolle
     */
    public function setRolle(Rolle $rolle)
    {
        $this->rolle = $rolle;
    }

    /**
     * @return boolean
     */
    public function isPlanIst()
    {
        return $this->planIst;
    }

    /**
     * @param boolean $planIst
     */
    public function setPlanIst($planIst)
    {
        $this->planIst = $planIst;
    }

    public function dauerIst()
    {
        if ($this->datumEndeIst && $this->datumStartIst){
            return ($this->datumEndeIst - $this->datumStartIst);
        }
        return;

    }

    public function dauerPlan()
    {
        if ($this->datumEndePlan && $this->datumStartPlan){
            return ($this->datumEndePlan - $this->datumStartPlan);
        }
        return;

    }

    public function dauerVergleich()
    {
        return $this->dauerIst() - $this->dauerPlan();
    }
}