<?php

namespace LPSIO\PlateformeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LPSIOPlateformeBundle:Default:index.html.twig');
    }

    public function aboutAction()
    {
        return $this->render('LPSIOPlateformeBundle:Default:about.html.twig');
    }

    public function contactAction()
    {
        return $this->render('LPSIOPlateformeBundle:Default:contact.html.twig');
    }
}
