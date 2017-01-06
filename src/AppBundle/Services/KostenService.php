<?php namespace AppBundle\Services;

use AppBundle\Entity\Projekt;
use AppBundle\Entity\Rolletyp;

/** USES */
class KostenService
{

    /**
     * Collects all necessary data from the database and save it in private attributes.
     *
     * @param Projekt $projekt
     * @param array | Rolletyp $rollenTypen
     * @return int
     */
    public function kostenBerechnenByType($projekt, $rollenTypen)
    {
        $kosten = 0;

        foreach ($rollenTypen as $typ){
            $alleRollen = $projekt->getRollen();
            foreach ($alleRollen as $rolle) {
                if ($rolle->getRolletyp() === $typ) {
                    $kosten = $kosten + $rolle->getKostenInklMwsT($rolle->getKostenPlan());
                }
            }
        }
        return $kosten;
    }

    /**
     * Collects all necessary data from the database and save it in private attributes.
     *
     * @param Projekt $projekt
     * @return int
     */
    public function kostenBerechnenGesamt($projekt)
    {
        $kosten = 0;

//        Rollen
        $alleRollen = $projekt->getRollen();
        foreach ($alleRollen as $rolle) {
            $kosten = $kosten + $rolle->getKostenInklMwsT($rolle->getKostenPlan());
        }

//        Grundstück
        $kosten = $kosten + $projekt->getGrundstueckspreis();

//        Haus
        $kosten = $kosten + $projekt->getHauskaufpreis();

        return $kosten;
    }
}
