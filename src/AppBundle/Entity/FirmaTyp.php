<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="firma_typ")
 */
class FirmaTyp extends AbstractBasicEntity
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $firmaTypName;

    /**
     * FirmaTyp constructor.
     * @param string $firmaTypName
     */
    public function __construct($firmaTypName)
    {
        $this->firmaTypName = $firmaTypName;
    }

    /**
     * @return string
     */
    public function getFirmaTypName()
    {
        return $this->firmaTypName;
    }

    /**
     * @param string $firmaTypName
     */
    public function setFirmaTypName($firmaTypName)
    {
        $this->firmaTypName = $firmaTypName;
    }




}