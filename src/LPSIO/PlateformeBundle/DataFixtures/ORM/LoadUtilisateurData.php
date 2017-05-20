<?php

namespace LPSIO\PlateformeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use LPSIO\PlateformeBundle\Entity\Utilisateur;


class LoadUtilisateurData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $utilisateur = new Utilisateur();
        $administrateur = new Utilisateur();

        $utilisateur->setNom("HAYET");
        $utilisateur->setPrenom("Marc-Antoine");
        $utilisateur->setCourriel("marcantoinehayet@gmail.com");
        $utilisateur->setUsername("marcantoinehayet@gmail.com");
        $utilisateur->setAdresse("7 HAMEAU DE MARSIGNIES");
        $utilisateur->setVille("COUSOLRE");
        $utilisateur->setPays("FRANCE");
        $utilisateur->setDateInscription(new \DateTime());
        $utilisateur->setPassword("130795");
        $utilisateur->setSalt('');
        $utilisateur->setRoles(array('ROLE_USER'));

        $administrateur->setNom("BENAMEUR");
        $administrateur->setPrenom("Nasser");
        $administrateur->setCourriel("nasser.benameur@univ-valenciennes.fr ");
        $administrateur->setUsername("nasser.benameur@univ-valenciennes.fr ");
        $administrateur->setAdresse("n/r");
        $administrateur->setVille("n/r");
        $administrateur->setPays("FRANCE");
        $administrateur->setDateInscription(new \DateTime());
        $administrateur->setPassword("1234");
        $administrateur->setSalt('');
        $administrateur->setRoles(array('ROLE_SUPER_ADMIN'));

        $manager->persist($utilisateur);
        $manager->persist($administrateur);

        $manager->flush();
    }
}