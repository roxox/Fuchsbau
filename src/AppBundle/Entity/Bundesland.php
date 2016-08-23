<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Bundesland  extends AbstractBasicEntity
{

    /**
     * var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * var string
     * @ORM\Column(type="string")
     */
    private $kurzname;

    /**
     * var string
     * @ORM\Column(type="float")
     */
    private $grunderwerbssteuersatz;

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
     * @return string
     */
    public function getKurzname()
    {
        return $this->kurzname;
    }

    /**
     * @param string $kurzname
     */
    public function setKurzname($kurzname)
    {
        $this->kurzname = $kurzname;
    }

    /**
     * @return float
     */
    public function getGrunderwerbssteuersatz()
    {
        return $this->grunderwerbssteuersatz;
    }

    /**
     * @param float $grunderwerbssteuersatz
     */
    public function setGrunderwerbssteuersatz($grunderwerbssteuersatz)
    {
        $this->grunderwerbssteuersatz = $grunderwerbssteuersatz;
    }



}