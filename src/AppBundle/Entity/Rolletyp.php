<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Rolletyp  extends AbstractBasicEntity
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
     * Rolletyp constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
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
     * @return mixed
     */
    public function getKurzname()
    {
        return $this->kurzname;
    }

    /**
     * @param mixed $kurzname
     */
    public function setKurzname($kurzname)
    {
        $this->kurzname = $kurzname;
    }
}