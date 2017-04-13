<?php

namespace LPSIO\PlateformeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LPSIOPlateformeBundle:Default:index.html.twig');
    }
}
