<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Grundstueck  extends AbstractBasicEntity
{
    /**
     * var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $strasse;

    /**
     * var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $hausnummer;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $zusatz;

    /**
     * var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $postleitzahl;

    /**
     * var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $ort;

    /**
     * var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $groesse;

    /**
     * var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $kaufpreis;

    /**
     * var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $erschiessungskostenanteil;

    /**
     * @var Bundesland
     *
     * @ORM\ManyToOne(targetEntity="Bundesland")
     */
    private $bundesland;

    /**
     * @var Projekt
     *
     * @ORM\OneToOne(targetEntity="Projekt", inversedBy="grundstueck", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="projekt_id", referencedColumnName="id")
     */
    private $projekt;

    /**
     * var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $grunderwerbssteuersatz;

    /**
     * Getter und Setter
     */

    /**
     * @return mixed
     */
    public function getStrasse()
    {
        return $this->strasse;
    }

    /**
     * @param mixed $strasse
     */
    public function setStrasse($strasse)
    {
        $this->strasse = $strasse;
    }

    /**
     * @return mixed
     */
    public function getHausnummer()
    {
        return $this->hausnummer;
    }

    /**
     * @param mixed $hausnummer
     */
    public function setHausnummer($hausnummer)
    {
        $this->hausnummer = $hausnummer;
    }

    /**
     * @return mixed
     */
    public function getPostleitzahl()
    {
        return $this->postleitzahl;
    }

    /**
     * @param mixed $postleitzahl
     */
    public function setPostleitzahl($postleitzahl)
    {
        $this->postleitzahl = $postleitzahl;
    }

    /**
     * @return mixed
     */
    public function getOrt()
    {
        return $this->ort;
    }

    /**
     * @param mixed $ort
     */
    public function setOrt($ort)
    {
        $this->ort = $ort;
    }

    /**
     * @return mixed
     */
    public function getGroesse()
    {
        return $this->groesse;
    }

    /**
     * @param mixed $groesse
     */
    public function setGroesse($groesse)
    {
        $this->groesse = $groesse;
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
     * @return mixed
     */
    public function getErschiessungskostenanteil()
    {
        return $this->erschiessungskostenanteil;
    }

    /**
     * @param mixed $erschiessungskostenanteil
     */
    public function setErschiessungskostenanteil($erschiessungskostenanteil)
    {
        $this->erschiessungskostenanteil = $erschiessungskostenanteil;
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
        if (!$projekt->getGrundstueck()) {
            $projekt->setGrundstueck($this);
        }
    }

    /**
     * @return string
     */
    public function getZusatz()
    {
        return $this->zusatz;
    }

    /**
     * @param string $zusatz
     */
    public function setZusatz($zusatz)
    {
        $this->zusatz = $zusatz;
    }

    /**
     * @return Bundesland
     */
    public function getBundesland()
    {
        return $this->bundesland;
    }

    /**
     * @param Bundesland $bundesland
     */
    public function setBundesland($bundesland)
    {
        $this->bundesland = $bundesland;
    }
//
//    /**
//     * @return float
//     */
//    public function getGrunderwerbssteuersatz()
//    {
//        return $this->grunderwerbssteuersatz;
//    }
//
//    /**
//     * @param float $grunderwerbssteuersatz
//     */
//    public function setGrunderwerbssteuersatz($grunderwerbssteuersatz)
//    {
//        $this->grunderwerbssteuersatz = $grunderwerbssteuersatz;
//    }

}