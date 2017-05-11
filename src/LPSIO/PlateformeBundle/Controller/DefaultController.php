<?php

namespace LPSIO\PlateformeBundle\Controller;

use LPSIO\PlateformeBundle\Entity\Contact;
use LPSIO\PlateformeBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

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

    public function loginAction(Request $request)
    {
        return $this->render('LPSIOPlateformeBundle:Default:login.html.twig');
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

        return $this->render('LPSIOPlateformeBundle:Default:visualiser-utilisateurs.html.twig', array('offres' => $offres));
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
        $utilisateur = new Utilisateur();

        $builder = $this->createFormBuilder($utilisateur)
            ->add('nom', TextType::class, array('label' => 'Nom'))
            ->add('prenom', TextType::class, array('label' => 'Prénom'))
            ->add('adresse', TextType::class, array('label' => 'Adresse'))
            ->add('ville', TextType::class, array('label' => 'Ville'))
            ->add('pays', TextType::class, array('label' => 'Pays'))
            ->add('courriel', EmailType::class, array('label' => 'Courriel'))
            ->add('motDePasse', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array('label' => 'Mot de passe'),
                'second_options' => array('label' => 'Répétez le mot de passe')))
            ->add('reset', ResetType::class, array('label' => 'Réinitialisation'))
            ->add('save', SubmitType::class, array('label' => 'Valider'));

        $form = $builder->getForm();

        return $this->render('LPSIOPlateformeBundle:Default:inscription.html.twig', array('form' => $form->createView()));
    }

    public function modifierOffreAction($idOffre)
    {
        $repositoryOffre = $this->getDoctrine()->getRepository('LPSIOPlateformeBundle:Offre');

        $offre = $repositoryOffre->find($idOffre);

        if(!$offre)
        {
            throw new NotFoundHttpException("L'offre ".$idOffre." n'existe pas.");
        }

        return $this->render('LPSIOPlateformeBundle:Administration:modifier-offre.html.twig', array('offre' => $offre));
    }

    public function visualiserUtilisateursAction()
    {
        return $this->render('LPSIOPlateformeBundle:Default:visualiser-utilisateurs.html.twig');
    }

    public function visualiserOffresAction()
    {
        return $this->render('LPSIOPlateformeBundle:Default:about.html.twig');
    }
}
