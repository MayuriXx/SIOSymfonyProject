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

        $utilisateur->setNom("HAYET");
        $utilisateur->setPrenom("Marc-Antoine");
        $utilisateur->setCourriel("marcantoinehayet@gmail.com");
        $utilisateur->setUsername("marcantoinehayet@gmail.com");
        $utilisateur->setAdresse("7 HAMEAU DE MARSIGNIES, 59149 COUSOLRE");
        $utilisateur->setVille("COUSOLRE");
        $utilisateur->setPays("FRANCE");
        $utilisateur->setDateInscription(new \DateTime());
        $utilisateur->setPassword("130795");
        $utilisateur->setSalt('');
        $utilisateur->setRoles(array('ROLE_ADMIN'));

        $manager->persist($utilisateur);

        $manager->flush();
    }
}