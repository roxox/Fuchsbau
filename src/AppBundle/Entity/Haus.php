<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Haus extends AbstractBasicEntity
{
    /**
     * var float
     * @ORM\Column(type="float")
     */
    private $wohnflaecheDin;

    /**
     * var float
     * @ORM\Column(type="float")
     */
    private $wohnflaecheWoFiv;

    /**
     * var float
     * @ORM\Column(type="float")
     */
    private $kaufpreis;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $preisInklMwSt = true;

    /**
     * @var Haustyp
     *
     * @ORM\ManyToOne(targetEntity="Haustyp")
     */
    private $haustyp;

    /**
     * @var Haustyp
     *
     * @ORM\ManyToOne(targetEntity="Kataloghaus")
     */
    private $kataloghaus;

    /**
     * @var Projekt
     *
     * @ORM\OneToOne(targetEntity="Projekt", inversedBy="haus", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="projekt_id", referencedColumnName="id")
     */
    private $projekt;

    /**
     * Funktionen
     */

    /**
     * @return mixed
     */
    public function getKaufpreisInklMwst()
    {
        if ($this->preisInklMwSt === true) {
            return $this->kaufpreis;
        } else {
            return $this->kaufpreis * 1.19;
        }
    }

    /**
     * @return mixed
     */
    public function getKaufpreisExklMwst()
    {
        if ($this->preisInklMwSt === true) {
            return $this->kaufpreis / 1.19;
        } else {
            return $this->kaufpreis;
        }
    }

    /**
     * Getter und Setter
     */

    /**
     * @return Haustyp
     */
    public function getHaustyp()
    {
        return $this->haustyp;
    }

    /**
     * Set hausTyp
     *
     * @param Haustyp $haustyp
     * @return Haus
     */
    public function setHaustyp(Haustyp $haustyp = null)
    {
        $this->haustyp = $haustyp;
        return $this;
    }

    public function getHausName()
    {
        // ToDo: sobald Kataloghäuser exisiteren, vorrangig Katalognamen verwenden
        return $this->haustyp->getName();
    }

    /**
     * @return integer
     */
    public function getWohnflaecheDin()
    {
        return $this->wohnflaecheDin;
    }

    /**
     * @param integer $wohnflaecheDin
     */
    public function setWohnflaecheDin($wohnflaecheDin)
    {
        $this->wohnflaecheDin = $wohnflaecheDin;
    }

    /**
     * @return integer
     */
    public function getWohnflaecheWoFiv()
    {
        return $this->wohnflaecheWoFiv;
    }

    /**
     * @param integer $wohnflaecheWoFiv
     */
    public function setWohnflaecheWoFiv($wohnflaecheWoFiv)
    {
        $this->wohnflaecheWoFiv = $wohnflaecheWoFiv;
    }

    /**
     * @return Haustyp
     */
    public function getKataloghaus()
    {
        return $this->kataloghaus;
    }

    /**
     * @param Haustyp $kataloghaus
     */
    public function setKataloghaus($kataloghaus)
    {
        $this->kataloghaus = $kataloghaus;
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
        if (!$projekt->getHaus()) {
            $projekt->setHaus($this);
        }
    }

    /**
     * @return mixed
     */
    public function getKaufpreis()
    {
        return $this->kaufpreis;
    }

    /**
     * @param mixed $kaufpreis
     */
    public function setKaufpreis($kaufpreis)
    {
        $this->kaufpreis = $kaufpreis;
    }

    /**
     * @return boolean
     */
    public function isPreisInklMwSt()
    {
        return $this->preisInklMwSt;
    }

    /**
     * @param boolean $preisInklMwSt
     */
    public function setPreisInklMwSt($preisInklMwSt)
    {
        $this->preisInklMwSt = $preisInklMwSt;
    }

}