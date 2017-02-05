<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class RollenGroup extends AbstractBasicEntity
{

    /**
     * var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var Projekt
     *
     * @ORM\ManyToOne(targetEntity="Projekt", inversedBy="rollenGroups", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="projekt_id", referencedColumnName="id", onDelete="set null")
     */
    private $projekt;

    /**
     * @ORM\OneToMany(targetEntity="Rolle", mappedBy="rollenGroup")
     */
    private $rollen;

    /**
     * Address constructor.
     */
    public function __construct()
    {
        $this->rollen = new ArrayCollection();
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
     * @return array|Rolle[]
     */
    public function getRollen()
    {
        return $this->rollen->toArray();
    }

    /**
     * @param Rolle $rolle
     * @return self
     */
    public function addRolle(Rolle $rolle)
    {
        $this->rollen->add($rolle);
        $rolle->setRollenGroup($this);

        return $this;
    }

    /**
     * @param Rolle $rolle
     * @return bool
     */
    public function hasRolle(Rolle $rolle)
    {
        return $this->rollen->contains($rolle);
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
        if (!$projekt->hasRollenGroup($this)) {
            $projekt->addRollenGroup($this);
        }
    }

}