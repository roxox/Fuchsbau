<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Adresse;
use AppBundle\Entity\Email;
use AppBundle\Entity\Firma;
use AppBundle\Entity\Geschlecht;
use AppBundle\Entity\Person;
use AppBundle\Entity\PersonenTitel;
use AppBundle\Entity\PersonTyp;
use AppBundle\Entity\PrivatGeschaeft;
use AppBundle\Entity\Projekt;
use AppBundle\Entity\Rolle;
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
        # USER #
        ########


        # ADMIN

        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setPassword('test');
        $userAdmin->setEmail('admin@roxox.de');

        # BAUHERR

        $userBauherr = new User();
        $userBauherr->setUsername('mira');
        $userBauherr->setPassword('test');
        $userBauherr->setEmail('mira@roxox.de');
        $userBauherr->addUserGroup($groupBauherr);


        ############
        # PERSONEN #
        ############

        $bauherr = new Person($userBauherr);
        $bauherr->setVorname('Mira');
        $bauherr->setNachname('Fox');

        #####################
        # PRIVAT / GESCHÄFT #
        #####################

        $privat = new PrivatGeschaeft();
        $privat->setName('Privat');
        $manager->persist($privat);

        $geschaeft = new PrivatGeschaeft();
        $geschaeft->setName('Arbeit');
        $manager->persist($geschaeft);

        ##############
        # GESCHLECHT #
        ##############

        $frau = new Geschlecht();
        $frau->setName('Frau');
        $manager->persist($frau);

        $herr = new Geschlecht();
        $herr->setName('Herr');
        $manager->persist($herr);

        $bauherr->setGeschlecht($frau);
        $manager->persist($bauherr);

        ##############
        # GESCHLECHT #
        ##############

        $dr = new PersonenTitel();
        $dr->setName('Dr.');
        $manager->persist($dr);

        $diplIng = new PersonenTitel();
        $diplIng->setName('Dipl. Ing.');
        $manager->persist($diplIng);

        $prof = new PersonenTitel();
        $prof->setName('Prof.');
        $manager->persist($prof);

        $manager->persist($bauherr);

        ###########
        # ADRESSE #
        ###########

        $adressePrivat = new Adresse('Baumschulenweg', '1a', '25373', 'Ellerhoop', true);
        $adressePrivat->setPrivatGeschaeft($privat);
        $adresseArbeit = new Adresse('Weg beim Jäger', '193', '22335', 'Hamburg', false);
        $adresseArbeit->setPrivatGeschaeft($geschaeft);
        $bauherr->addAdresse($adressePrivat);
        $bauherr->addAdresse($adresseArbeit);

        #################
        # TELEFONNUMMER #
        #################

        $telefonTypMobil = new TelefonTyp();
        $telefonTypMobil->setName('Mobil');
        $telefonTypMobil->setKurzname('Mobil');

        $telefonTypTelefon = new TelefonTyp();
        $telefonTypTelefon->setName('Telefon');
        $telefonTypTelefon->setKurzname('Tel.');

        $telefonnummerMobil = new Telefonnummer();
        $telefonnummerMobil->setVorwahl('0176');
        $telefonnummerMobil->setTelefonnummer('20516474');
        $telefonnummerMobil->setPrivatGeschaeft($privat);
        $telefonnummerMobil->setTelefonTyp($telefonTypMobil);

//        $telefonnummerArbeit = new Telefonnummer('040', '5070');
//        $telefonnummerArbeit->setDurchwahl('41772');
//        $telefonnummerArbeit->setPrivatGeschaeft($geschaeft);
//        $telefonnummerArbeit->setTelefonTyp($telefonTypTelefon);

        $bauherr->addTelefonnummer($telefonnummerMobil);
//        $bauherr->addTelefonnummer($telefonnummerArbeit);

        $manager->persist($bauherr);

        #########
        # EMAIL #
        #########

        $emailPrivat = new Email('mira@me.com');
        $emailPrivat->setPrivatGeschaeft($privat);

        $bauherr->addEmailadresse($emailPrivat);
        $manager->persist($bauherr);

        ##########
        # ROLLEN #
        ##########

        $rolleBauherr = new Rolle('Bauherr');
        $rolleMaurer = new Rolle ('Maurer');

        $rolleBauherr->addPerson($bauherr);

        ##########
        # FIRMEN #
        ##########

        $firmaMaurer = new Firma('Maurer GmbH');
        $rolleMaurer->setFirma($firmaMaurer);

        ###########
        # PROJEKT #
        ###########

        $project = new Projekt();
        $project->setStrasse('Baumschulenweg');
        $project->setHausnummer('1a');
        $project->setPostleitzahl('25373');
        $project->setOrt('Ellerhoop');
        $project->addRolle($rolleBauherr);
        $project->addRolle($rolleMaurer);
        $project->addUser($userBauherr);
        $project->setOwner($userBauherr);
        $project->setProjektName('Baumschulenweg 1a, 25373 Ellerhoop');
        $project->setEinladungscode('xxx');

        $manager->persist($project);


        $manager->flush();
    }
}