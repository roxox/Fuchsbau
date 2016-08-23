<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="nachricht")
 */
class Nachricht  extends AbstractBasicEntity
{

    /**
     * var string
     * @ORM\Column(type="string")
     */
    private $betreff = '[Ohne Betreff]';

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $nachricht;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $gesendetAm;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="User", cascade={"remove", "persist"})
     */
    private $absender;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="User", cascade={"remove", "persist"})
     */
    private $empfaenger;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $gelesen = false;

    /**
     * Rolletyp constructor.
     * @param $absender
     */
    public function __construct($absender)
    {
        $this->absender = $absender;
    }

    /**
     * @return mixed
     */
    public function getBetreff()
    {
        return $this->betreff;
    }

    /**
     * @param mixed $betreff
     */
    public function setBetreff($betreff)
    {
        $this->betreff = $betreff;
    }

    /**
     * @return string
     */
    public function getNachricht()
    {
        return $this->nachricht;
    }

    /**
     * @param string $nachricht
     */
    public function setNachricht($nachricht)
    {
        $this->nachricht = $nachricht;
    }

    /**
     * @return \DateTime
     */
    public function getGesendetAm()
    {
        return $this->gesendetAm;
    }

    /**
     * @param \DateTime $gesendetAm
     */
    public function setGesendetAm($gesendetAm)
    {
        $this->gesendetAm = $gesendetAm;
    }

    /**
     * @return User
     */
    public function getAbsender()
    {
        return $this->absender;
    }

    /**
     * @param User $absender
     */
    public function setAbsender(User $absender)
    {
        $this->absender = $absender;
    }

    /**
     * @return User
     */
    public function getEmpfaenger()
    {
        return $this->empfaenger;
    }

    /**
     * @param User $empfaenger
     */
    public function setEmpfaenger(User $empfaenger)
    {
        $this->empfaenger = $empfaenger;
    }

    /**
     * @return boolean
     */
    public function isGelesen()
    {
        return $this->gelesen;
    }

    /**
     * @param boolean $gelesen
     */
    public function setGelesen($gelesen)
    {
        $this->gelesen = $gelesen;
    }

}