<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Adresse;
use AppBundle\Entity\Bundesland;
use AppBundle\Entity\Einheit;
use AppBundle\Entity\Email;
use AppBundle\Entity\Firma;
use AppBundle\Entity\Geschlecht;
use AppBundle\Entity\Grundstueck;
use AppBundle\Entity\Haustyp;
use AppBundle\Entity\Mehrwertsteuer;
use AppBundle\Entity\Nachricht;
use AppBundle\Entity\Person;
use AppBundle\Entity\PersonenTitel;
use AppBundle\Entity\PersonTyp;
use AppBundle\Entity\PrivatGeschaeft;
use AppBundle\Entity\Projekt;
use AppBundle\Entity\Rolle;
use AppBundle\Entity\Rolletyp;
use AppBundle\Entity\Telefonnummer;
use AppBundle\Entity\TelefonTyp;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use AppBundle\Entity\User;
use AppBundle\Entity\Group;

class LoadUserData implements FixtureInterface
{

    const ROLE_OWNER = 'ROLE_OWNER';
    const ROLE_BEOBACHTER = 'ROLE_BEOBACHTER';
    const ROLE_BEGLEITER = 'ROLE_BEGLEITER';
    const ROLE_BAUHERR = 'ROLE_BAUHERR';
    const ROLE_BAULEITER = 'ROLE_BAULEITER';
    const ROLE_BAUTRAEGER = 'ROLE_BAUTRAEGER';
    const ROLE_HANDWERK = 'ROLE_HANDWERK';

    const PRIVAT = 'Privat';
    const ARBEIT = 'Arbeit';

    const TELEFON = "Telefon";
    const FAX = "Fax";
    const MOBIL = "Mobil";
    const SONSTIGES = "Sonstiges";

    public function load(ObjectManager $manager)
    {
        #############
        # USERGROUP #
        #############

        $groupBauherr = new Group('Bauherr');
        $groupBauherr->addRole(static::ROLE_BAUHERR);

        $groupHandwerk = new Group('Handwerk');
        $groupHandwerk->addRole(static::ROLE_HANDWERK);

        ########
        # MWST #
        ########

        $mwst19inkl = new Mehrwertsteuer();
        $mwst19inkl->setInklusive(true);
        $mwst19inkl->setOhneMwSt(false);
        $mwst19inkl->setWert(19);
        $mwst19inkl->setBezeichnung('inkl. 19% MwSt.');
        $manager->persist($mwst19inkl);

        $mwst19exkl = new Mehrwertsteuer();
        $mwst19exkl->setInklusive(false);
        $mwst19exkl->setWert(19);
        $mwst19inkl->setOhneMwSt(false);
        $mwst19exkl->setBezeichnung('exkl. 19% MwSt.');
        $manager->persist($mwst19exkl);

        $mwst0inkl = new Mehrwertsteuer();
        $mwst0inkl->setInklusive(true);
        $mwst0inkl->setWert(0);
        $mwst0inkl->setOhneMwSt(true);
        $mwst0inkl->setBezeichnung('MwSt. entfällt');
        $manager->persist($mwst0inkl);

        #############
        # EINHEITEN #
        #############

        $einheit = new Einheit('g', 'Gramm');
        $manager->persist($einheit);
        $einheit = new Einheit('kg', 'Kilogramm');
        $manager->persist($einheit);
        $einheit = new Einheit('t', 'Tonne');
        $manager->persist($einheit);
        $einheit = new Einheit('m', 'Meter');
        $manager->persist($einheit);
        $einheit = new Einheit('m²', 'Quadratmeter');
        $manager->persist($einheit);
        $einheit = new Einheit('m³', 'Kubikmeter');
        $manager->persist($einheit);
        $einheit = new Einheit('Stück', 'Stück');
        $manager->persist($einheit);
        $einheit = new Einheit('Tag', 'Tag');
        $manager->persist($einheit);
        $einheit = new Einheit('Woche', 'Woche');
        $manager->persist($einheit);
        $einheit = new Einheit('Monat', 'Monat');
        $manager->persist($einheit);
        $einheit = new Einheit('Jahr', 'Jahr');
        $manager->persist($einheit);
        $einheit = new Einheit('Person', 'Person');
        $manager->persist($einheit);


        ############
        # Rolletyp #
        ############

        $rolletypPerson = new Rolletyp('Person');
        $rolletypPerson->setKurzname('P');
        $manager->persist($rolletypPerson);
        $rolletypTaetigkeit = new Rolletyp('Tätigkeit');
        $rolletypTaetigkeit->setKurzname('T');
        $manager->persist($rolletypTaetigkeit);
        $rolletypInternExtra = new Rolletyp('Internes Extra');
        $rolletypInternExtra->setKurzname('IE');
        $manager->persist($rolletypInternExtra);
        $rolletypExternExtra = new Rolletyp('Externes Extra');
        $rolletypExternExtra->setKurzname('EE');
        $manager->persist($rolletypExternExtra);
        $rolletypNebenkosten = new Rolletyp('Nebenkosten');
        $rolletypNebenkosten->setKurzname('N');
        $manager->persist($rolletypNebenkosten);
        $rolletypInneneinrichtung = new Rolletyp('Inneneinrichtung');
        $rolletypInneneinrichtung->setKurzname('I');
        $manager->persist($rolletypInneneinrichtung);
        $rolletypAussenanlagen = new Rolletyp('Außenanlagen');
        $rolletypAussenanlagen->setKurzname('A');
        $manager->persist($rolletypAussenanlagen);
        $rolletypAussenanlagen = new Rolletyp('Gutschrift');
        $rolletypAussenanlagen->setKurzname('G');
        $manager->persist($rolletypAussenanlagen);

        $internesExtra = new Rolle();
        $internesExtra->setName('Bodentiefe Dusche');
        $internesExtra->setRolletyp($rolletypInternExtra);
        $internesExtra->setMehrwertsteuer($mwst19inkl);
        $internesExtra->setKostenPlan(1100.50);
        $manager->persist($internesExtra);


        ########
        # USER #
        ########

        # BAUHERR/IN
        $katha = new User();
        $katha->setUsername('Katha');
        $katha->setPassword('test');
        $katha->setEmail('$atha@roxox.de');
        $katha->addUserGroup($groupBauherr);
        $manager->persist($katha);

        $basti = new User();
        $basti->setUsername('Batha');
        $basti->setPassword('test');
        $basti->setEmail('basti@roxox.de');
        $basti->addUserGroup($groupBauherr);
        $manager->persist($basti);

        ################
        # BUNDESLÄNDER #
        ################
        $bundeslaender = [];

        # Baden-Württemberg
        $bundesland = new Bundesland();
        $bundesland->setName('Baden-Württemberg');
        $bundesland->setGrunderwerbssteuersatz(5);
        $bundesland->setKurzname('BW');
        $bundeslaender[] = $bundesland;

        # Bayern
        $bundesland = new Bundesland();
        $bundesland->setName('Bayern');
        $bundesland->setGrunderwerbssteuersatz(3.5);
        $bundesland->setKurzname('BY');
        $bundeslaender[] = $bundesland;

        # Berlin
        $bundesland = new Bundesland();
        $bundesland->setName('Berlin');
        $bundesland->setGrunderwerbssteuersatz(6);
        $bundesland->setKurzname('BE');
        $bundeslaender[] = $bundesland;

        # Brandenburg
        $bundesland = new Bundesland();
        $bundesland->setName('Brandenburg');
        $bundesland->setGrunderwerbssteuersatz(6.5);
        $bundesland->setKurzname('BB');
        $bundeslaender[] = $bundesland;

        # Bremen
        $bundesland = new Bundesland();
        $bundesland->setName('Bremen');
        $bundesland->setGrunderwerbssteuersatz(5);
        $bundesland->setKurzname('HB');
        $bundeslaender[] = $bundesland;

        # Hamburg
        $bundesland = new Bundesland();
        $bundesland->setName('Hamburg');
        $bundesland->setGrunderwerbssteuersatz(4.5);
        $bundesland->setKurzname('HH');
        $bundeslaender[] = $bundesland;

        # Hessen
        $bundesland = new Bundesland();
        $bundesland->setName('Hessen');
        $bundesland->setGrunderwerbssteuersatz(6);
        $bundesland->setKurzname('HE');
        $bundeslaender[] = $bundesland;

        # Mecklenburg-Vorpommern
        $bundesland = new Bundesland();
        $bundesland->setName('Mecklenburg-Vorpommern');
        $bundesland->setGrunderwerbssteuersatz(5);
        $bundesland->setKurzname('MV');
        $bundeslaender[] = $bundesland;

        # Niedersachsen
        $bundesland = new Bundesland();
        $bundesland->setName('Niedersachsen');
        $bundesland->setGrunderwerbssteuersatz(5);
        $bundesland->setKurzname('NI');
        $bundeslaender[] = $bundesland;

        # Nordrhein-Westfalen
        $bundesland = new Bundesland();
        $bundesland->setName('Nordrhein-Westfalen');
        $bundesland->setGrunderwerbssteuersatz(6.5);
        $bundesland->setKurzname('NW');
        $bundeslaender[] = $bundesland;

        # Rheinland-Pfalz
        $bundesland = new Bundesland();
        $bundesland->setName('Rheinland-Pfalz');
        $bundesland->setGrunderwerbssteuersatz(5);
        $bundesland->setKurzname('RP');
        $bundeslaender[] = $bundesland;

        # Saarland
        $bundesland = new Bundesland();
        $bundesland->setName('Saarland');
        $bundesland->setGrunderwerbssteuersatz(6.5);
        $bundesland->setKurzname('SL');
        $bundeslaender[] = $bundesland;

        # Sachsen
        $bundesland = new Bundesland();
        $bundesland->setName('Sachsen');
        $bundesland->setGrunderwerbssteuersatz(3.5);
        $bundesland->setKurzname('SN');
        $bundeslaender[] = $bundesland;

        # Sachsen-Anhalt
        $bundesland = new Bundesland();
        $bundesland->setName('Sachsen-Anhalt');
        $bundesland->setGrunderwerbssteuersatz(5);
        $bundesland->setKurzname('ST');
        $bundeslaender[] = $bundesland;

        # Thüringen
        $bundesland = new Bundesland();
        $bundesland->setName('Thüringen');
        $bundesland->setGrunderwerbssteuersatz(5);
        $bundesland->setKurzname('TH');
        $bundeslaender[] = $bundesland;

        # Schleswig Holstein
        $bundesland = new Bundesland();
        $bundesland->setName('Schleswig Holstein');
        $bundesland->setGrunderwerbssteuersatz(6.5);
        $bundesland->setKurzname('SH');
        $bundeslaender[] = $bundesland;

        foreach ($bundeslaender as $bundesland) {
            $manager->persist($bundesland);
        }

        #####################
        # PRIVAT / GESCHÄFT #
        #####################

        $privat = new PrivatGeschaeft();
        $privat->setName('Privat');
        $manager->persist($privat);

        $geschaeft = new PrivatGeschaeft();
        $geschaeft->setName('Arbeit');
        $manager->persist($geschaeft);

        ###########
        # HAUSTYP #
        ###########

        $friesenhaus = new Haustyp();
        $friesenhaus->setName('Friesenhaus');
        $friesenhaus->setKurzname('FH');
        $manager->persist($friesenhaus);

        $einfamilienhaus = new Haustyp();
        $einfamilienhaus->setName('Einfamilienhaus');
        $einfamilienhaus->setKurzname('EFH');
        $manager->persist($einfamilienhaus);

        $doppelhaushaelfte = new Haustyp();
        $doppelhaushaelfte->setName('Doppelhaushälfte');
        $doppelhaushaelfte->setKurzname('DHH');
        $manager->persist($doppelhaushaelfte);

        $stadtvilla = new Haustyp();
        $stadtvilla->setName('Stadvilla');
        $stadtvilla->setKurzname('SV');
        $manager->persist($stadtvilla);


        ##############
        # GESCHLECHT #
        ##############

        $frau = new Geschlecht();
        $frau->setName('Frau');
        $manager->persist($frau);

        $herr = new Geschlecht();
        $herr->setName('Herr');
        $manager->persist($herr);

        #########
        # TITEL #
        #########

        $dr = new PersonenTitel();
        $dr->setName('Dr.');
        $manager->persist($dr);

        $diplIng = new PersonenTitel();
        $diplIng->setName('Dipl. Ing.');
        $manager->persist($diplIng);

        $prof = new PersonenTitel();
        $prof->setName('Prof.');
        $manager->persist($prof);

        #################
        # TELEFONNUMMER #
        #################

        $telefonTypMobil = new TelefonTyp();
        $telefonTypMobil->setName('Mobil');
        $telefonTypMobil->setKurzname('Mobil');
        $manager->persist($telefonTypMobil);

        $telefonTypTelefon = new TelefonTyp();
        $telefonTypTelefon->setName('Telefon');
        $telefonTypTelefon->setKurzname('Tel.');
        $manager->persist($telefonTypTelefon);

        ########
        # MAIL #
        ########

        $mail = new Nachricht($katha);
        $mail->setNachricht('Hallo, das ist meine erste Nachricht :-)');
        $mail->setEmpfaenger($basti);
        $timestamp = new \DateTime();
        $mail->setGesendetAm($timestamp);
        $manager->persist($mail);

        ########
        # TEST #
        ########

        $newRolle = new Rolle();
        $newRolle->setName('Showerboard');
        $newRolle->setRolletyp($rolletypInternExtra);
        $newRolle->setMehrwertsteuer($mwst19inkl);
        $newRolle->setKostenPlan(700.95);
        $newRolle->setParent($internesExtra);
        $internesExtra->addChild($newRolle);
        $manager->persist($newRolle);

        $manager->flush();
    }
}