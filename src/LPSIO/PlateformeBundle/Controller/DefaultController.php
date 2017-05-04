<?php

namespace LPSIO\PlateformeBundle\Controller;

use LPSIO\PlateformeBundle\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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

    public function offreAction($idOffre)
    {
        return $this->render('LPSIOPlateformeBundle:Default:offre.html.twig', array('offre' => $idOffre));
    }

    public function offresAction()
    {
        return $this->render('LPSIOPlateformeBundle:Default:offres.html.twig');
    }

    public function contactAction()
    {
        $contact = new Contact();

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $contact);

        $formBuilder = $this->createFormBuilder($contact)
            ->add('nom', TextType::class, array('label' => 'Nom'))
            ->add('prenom', TextType::class, array('label' => 'Prénom'))
            ->add('courriel', EmailType::class, array('label' => 'Courriel'))
            ->add('message', TextareaType::class, array('label' => 'Message'))
            ->add('save', SubmitType::class, array('label' => 'Envoyer'));

        $form = $formBuilder->getForm();

        return $this->render('LPSIOPlateformeBundle:Default:contact.html.twig', array('form' => $form->createView()));
    }

    public function inscriptionAction()
    {
        return $this->render('LPSIOPlateformeBundle:Default:inscription.html.twig');
    }
}
