<?php

namespace LPSIO\PlateformeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use LPSIO\PlateformeBundle\Entity\Offre;

class LoadOffreData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i <= 10; $i++)
        {
            $offre = new Offre();

            $offre->setTitre("Contrat d'apprentissage DevTech");
            $offre->setDescription("Martinus agens illas provincias pro praefectis aerumnas innocentium graviter gemens saepeque obsecrans, ut ab omni culpa inmunibus parceretur, cum non inpetraret, minabatur se discessurum: ut saltem id metuens perquisitor malivolus tandem desineret quieti coalitos homines in aperta pericula proiectare.");
            $offre->setDuree(160);
            $offre->setType(1);
            $offre->setSalaire(1500);
            $offre->setDateCreation(new \DateTime('2017-1-1'));
            $offre->setDateDebut(new \DateTime('2017-5-1'));
            $offre->setDateFin(new \DateTime('2018-5-1'));
            $offre->setStatus(1);

            $manager->persist($offre);
        }

        $manager->flush();
    }
}