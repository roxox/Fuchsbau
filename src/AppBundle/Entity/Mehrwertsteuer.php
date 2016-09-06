<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Mehrwertsteuer  extends AbstractBasicEntity
{

    /**
     * var string
     * @ORM\Column(type="string")
     */
    private $bezeichnung;

    /**
     * var integer
     * @ORM\Column(type="integer")
     */
    private $wert;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $inklusive = false;

    /**
     * @return string
     */
    public function getBezeichnung()
    {
        return $this->bezeichnung;
    }

    /**
     * @param string $bezeichnung
     */
    public function setBezeichnung($bezeichnung)
    {
        $this->bezeichnung = $bezeichnung;
    }

    /**
     * @return integer
     */
    public function getWert()
    {
        return $this->wert;
    }

    /**
     * @param integer $wert
     */
    public function setWert($wert)
    {
        $this->wert = $wert;
    }

    /**
     * @return boolean
     */
    public function isInklusive()
    {
        return $this->inklusive;
    }

    /**
     * @param boolean $inklusive
     */
    public function setInklusive($inklusive)
    {
        $this->inklusive = $inklusive;
    }


}