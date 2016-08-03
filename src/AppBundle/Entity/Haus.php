<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Haus  extends AbstractBasicEntity
{

    /**
     * var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * var integer
     * @ORM\Column(type="integer")
     */
    private $wohnflaecheDin;

    /**
     * var integer
     * @ORM\Column(type="integer")
     */
    private $wohnflaecheWoFiv;

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

}