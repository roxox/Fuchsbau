<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="person_typ")
 */
class PersonTyp extends AbstractBasicEntity
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $personTypName;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Person", mappedBy="adressen")
     */
    private $personen;

    /**
     * PersonTyp constructor.
     * @param string $personTypName
     */
    public function __construct($personTypName)
    {
        $this->personTypName = $personTypName;
        $this->personen = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getPersonTypName()
    {
        return $this->personTypName;
    }

    /**
     * @param string $personTypName
     */
    public function setPersonTypName($personTypName)
    {
        $this->personTypName = $personTypName;
    }

    /**
     * @return array|Person[]
     */
    public function getPersonen()
    {
        return $this->personen->toArray();
    }

    /**
     * @param Person $person
     * @return self
     */
    public function addPerson(Person $person)
    {
        $this->personen->add($person);

        if (!$person->hasPersonTyp($this)) {
            $person->addPersonTyp($this);
        }

        return $this;
    }

    /**
     * @param Person $person
     * @return bool
     */
    public function hasPerson(Person $person)
    {
        return $this->personen->contains($person);
    }

}