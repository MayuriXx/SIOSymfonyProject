<?php

namespace LPSIO\PlateformeBundle\Controller;

use LPSIO\PlateformeBundle\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller

{
    public function indexAction()
    {
        $repositoryOffre = $this->getDoctrine()->getRepository('LPSIOPlateformeBundle:Offre');

        $offres = $repositoryOffre->findBy(
            array(),
            array('dateCreation' => 'DESC'),
            3,
            0
        );

        return $this->render('LPSIOPlateformeBundle:Default:index.html.twig', array('offres' => $offres));
    }

    public function informationsAction()
    {
        return $this->render('LPSIOPlateformeBundle:Default:mes-informations.html.twig');
    }

    public function aboutAction()
    {
        return $this->render('LPSIOPlateformeBundle:Default:about.html.twig');
    }

    public function alertesAction()
    {
        return $this->render('LPSIOPlateformeBundle:Default:alertes.html.twig');
    }

    public function offreAction($idOffre)
    {
        $repositoryOffre = $this->getDoctrine()->getRepository('LPSIOPlateformeBundle:Offre');

        $offre = $repositoryOffre->find($idOffre);

        if(!$offre)
        {
            throw new NotFoundHttpException("L'offre ".$idOffre." n'existe pas.");
        }

        return $this->render('LPSIOPlateformeBundle:Default:offre.html.twig', array('offre' => $offre));
    }

    public function offresAction()
    {
        $repositoryOffre = $this->getDoctrine()->getRepository('LPSIOPlateformeBundle:Offre');

        $offres = $repositoryOffre->findBy(
            array(),
            array('dateCreation' => 'DESC'),
            null,
            null
        );

        return $this->render('LPSIOPlateformeBundle:Default:offres.html.twig', array('offres' => $offres));
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
            ->add('reset', ResetType::class, array('label' => 'Réinitialisation'))
            ->add('save', SubmitType::class, array('label' => 'Envoyer'));

        $form = $formBuilder->getForm();

        return $this->render('LPSIOPlateformeBundle:Default:contact.html.twig', array('form' => $form->createView()));
    }

    public function inscriptionAction()
    {
        return $this->render('LPSIOPlateformeBundle:Default:inscription.html.twig');
    }
}
