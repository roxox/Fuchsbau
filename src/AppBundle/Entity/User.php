<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;



/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var User $createdBy
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $createdBy;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @var User $updatedBy
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $updatedBy;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Group", cascade={"remove", "persist"})
     * @ORM\JoinTable(name="user_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $userGroups;

    /**
     * @var Person
     *
     * @ORM\OneToOne(targetEntity="Person", mappedBy="user", cascade={"remove", "persist"})
     */
    private $person;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Projekt", mappedBy="users")
     */
    private $projekte;

    public function __construct()
    {
        parent::__construct();
        $this->userGroups = new ArrayCollection();
        $this->projekte = new ArrayCollection();
    }

    /**
     * addUserGroup - adds a group with one or more roles to an user.
     * @param Group $group
     * @return $this
     */
    public function addUserGroup(Group $group){

        $this->userGroups->add($group);

        return $this;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @return User
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    ##############
    # User Group #
    ##############

    /**
     * @return mixed
     */
    public function getUserGroups()
    {
        return $this->userGroups;
    }

    /**
     * @param mixed $userGroups
     */
    public function setUserGroups($userGroups)
    {
        $this->userGroups = $userGroups;
    }

    /**
     * @return ArrayCollection
     */
    public function getProjekte()
    {
        return $this->projekte->toArray();
    }

    /**
     * @param Projekt $projekt
     * @return self
     */
    public function addProjekt(Projekt $projekt)
    {
        $this->projekte->add($projekt);

        if (!$projekt->hasUser($this)) {
            $projekt->addUser($this);
        }

        return $this;
    }

    /**
     * @param Projekt $projekt
     * @return bool
     */
    public function hasProjekt(Projekt $projekt)
    {
        return $this->projekte->contains($projekt);
    }

    /**
     * @return Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param Person $person
     */
    public function setPerson($person)
    {
        $this->person = $person;
    }


}