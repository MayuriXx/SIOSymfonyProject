<?php

namespace LPSIO\PlateformeBundle\Controller;


use LPSIO\PlateformeBundle\Entity\Contact;
use LPSIO\PlateformeBundle\Entity\Offre;
use LPSIO\PlateformeBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
        $idUtilisateur = 2;
        $repositoryUtilisateur = $this->getDoctrine()->getRepository('LPSIOPlateformeBundle:Utilisateur');

        $utilisateur = $repositoryUtilisateur->find($idUtilisateur);

        if(!$utilisateur)
        {
            throw new NotFoundHttpException("L'utilisateur ".$idUtilisateur." n'existe pas.");
        }

        return $this->render('LPSIOPlateformeBundle:Default:mes-informations.html.twig',array('utilisateur' => $utilisateur));
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

        $builder = $this->createFormBuilder($offre)
            ->add('titre', TextType::class, array('label' => 'Titre'))
            ->add('description', TextareaType::class, array('label' => 'Description'))
            ->add('duree', IntegerType::class, array('label' => 'Durée'))
            ->add('type', IntegerType::class, array('label' => 'Type'))
            ->add('salaire', IntegerType::class, array('label' => 'Salaire (€)'))
            ->add('dateCreation', DateType::class, array('label' => 'Date de création'))
            ->add('dateDebut', DateType::class, array('label' => 'Date de début'))
            ->add('dateFin', DateType::class, array('label' => 'Date de fin'))
            ->add('visible', CheckboxType::class, array('label' => 'Visible'))
            ->add('save', SubmitType::class, array('label' => 'Sauvegarder'));

        $form = $builder->getForm();

        return $this->render('LPSIOPlateformeBundle:Administration:modifier-offre.html.twig', array('form' => $form->createView(),'offre'=> $offre));
    }

    public function visualiserUtilisateursAction()
    {
        $repositoryUtilisateur = $this->getDoctrine()->getRepository('LPSIOPlateformeBundle:Utilisateur');

        $utilisateurs = $repositoryUtilisateur->findBy(
            array(),
            array('nom' => 'ASC', 'prenom' => 'ASC'),
            null,
            null
        );
        return $this->render('LPSIOPlateformeBundle:Administration:visualiser-utilisateurs.html.twig', array('utilisateurs' => $utilisateurs));
    }

    public function visualiserOffresAction()
    {
        $repositoryOffre = $this->getDoctrine()->getRepository('LPSIOPlateformeBundle:Offre');

        $offres = $repositoryOffre->findBy
        (
            array(),
            array('dateCreation' => 'DESC'),
            null,
            null
        );

        return $this->render('LPSIOPlateformeBundle:Administration:visualiser-offres.html.twig',array('offres' => $offres));
    }

    public function creerOffreAction(Request $request)
    {
        $offre = new Offre();

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $offre);

        $formBuilder = $this->createFormBuilder($offre)
            ->add('titre', TextType::class, array('label' => 'Titre'))
            ->add('type', ChoiceType::class, array(
                'choices'  => array(
                    'Contrat d\'apprentissage' => 1,
                    'Contrat de professionnalisation' => 2,
                    'Stage' => 3,
                    'Autre' => 4)))
            ->add('description', TextareaType::class, array('label' => 'Description', 'required' => false))
            ->add('dateDebut', DateType::class, array('label' => 'Date de début'))
            ->add('dateFin', DateType::class, array('label' => 'Date de fin'))
            ->add('duree', IntegerType::class, array('label' => 'Durée'))
            ->add('salaire', IntegerType::class, array('label' => 'Salaire'))
            ->add('visible', CheckboxType::class, array('label' => 'Visible'))
            ->add('reset', ResetType::class, array('label' => 'Réinitialisation'))
            ->add('save', SubmitType::class, array('label' => 'Envoyer'));

        $form = $formBuilder->getForm();

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $offre->setDateCreation(new \DateTime('now'));

            $em = $this->getDoctrine()->getManager();

            $em->persist($offre);
            $em->flush();

            $this->addFlash('notice','Ajout de l\'offre réussie');
        }

        return $this->render('LPSIOPlateformeBundle:Administration:creer-offre.html.twig', array('form' => $form->createView()));
    }


    public function supprimerOffreAction($idOffre)
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryOffre = $this->getDoctrine()->getRepository('LPSIOPlateformeBundle:Offre');

        $offre = $repositoryOffre->find($idOffre);

        if(!$offre)
        {
            throw new NotFoundHttpException("L'offre ".$idOffre." n'existe pas.");
        }
        else
        {
            $this->addFlash('notice','Suppression de l\'offre  réussie.');

            $em->remove($offre);
            $em->flush();
        }

        return $this->redirectToRoute('lpsio_plateforme_administration_visualiser_offres');

    }


    public function modifierUtilisateurAction($idUtilisateur, Request $request)
    {
        $repositoryUtilisateur = $this->getDoctrine()->getRepository('LPSIOPlateformeBundle:Utilisateur');

        $utilisateur = $repositoryUtilisateur->find($idUtilisateur);

        if(!$utilisateur)
        {
            throw new NotFoundHttpException("L'utilisateur ".$idUtilisateur." n'existe pas.");
        }

        $builder = $this->createFormBuilder($utilisateur)
            ->add('nom', TextType::class, array('label' => 'Nom '))
            ->add('prenom', TextType::class, array('label' => 'Prénom '))
            ->add('courriel', EmailType::class, array('label' => 'Courriel '))
            ->add('adresse', TextType::class, array('label' => 'Adresse '))
            ->add('ville', TextType::class, array('label' => 'Ville '))
            ->add('pays', TextType::class, array('label' => 'Pays '))
            ->add('save', SubmitType::class, array('label' => 'Enregistrer '));

        $form = $builder->getForm();

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em->persist($utilisateur);
            $em->flush();

            $this->addFlash('notice','Modification de l\'utilisateur réussie');
        }

        return $this->render('LPSIOPlateformeBundle:Administration:modifier-utilisateur.html.twig', array('form' => $form->createView(),'utilisateur'=> $utilisateur));
    }


    public function supprimerUtilisateurAction($idUtilisateur)
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryUtilisateur = $this->getDoctrine()->getRepository('LPSIOPlateformeBundle:Utilisateur');
        $utilisateur = $repositoryUtilisateur->find($idUtilisateur);
        if(!$utilisateur)
        {
            throw new NotFoundHttpException("L'utilisateur ".$idUtilisateur." n'existe pas.");
        }
        else
        {
            $this->addFlash('notice','Suppression de l\'utilisateur  réussi.');
            $em->remove($utilisateur);
            $em->flush();
        }
        return $this->redirectToRoute('lpsio_plateforme_administration_visualiser_utilisateurs');
    }
}