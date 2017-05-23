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
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            return $this->redirectToRoute('lpsio_plateforme_homepage');
        }

        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('LPSIOPlateformeBundle:Default:login.html.twig', array('error' => $authenticationUtils->getLastAuthenticationError()));
    }

    public function informationsAction()
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            $utilisateur = $this->getUser();

            return $this->render('LPSIOPlateformeBundle:Default:mes-informations.html.twig', array('utilisateur' => $utilisateur));
        }
        else
        {
            return $this->redirectToRoute('lpsio_plateforme_login');
        }
    }

    public function modifierMesInformationsAction(Request $request)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            $utilisateur = $this->getUser();

            $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $utilisateur);

            $formBuilder = $this->createFormBuilder($utilisateur)
                ->add('nom', TextType::class, array('label' => 'Nom'))
                ->add('prenom', TextType::class, array('label' => 'Prénom'))
                ->add('courriel', EmailType::class, array('label' => 'Courriel'))
                ->add('adresse', TextType::class, array('label' => 'Adresse'))
                ->add('pays', TextType::class, array('label' => 'Pays'))
                ->add('reset', ResetType::class, array('label' => 'Réinitialisation'))
                ->add('save', SubmitType::class, array('label' => 'Envoyer'));

            $form = $formBuilder->getForm();

            if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
            {
                $em = $this->getDoctrine()->getManager();

                $em->persist($utilisateur);

                $em->flush();

                $this->addFlash('notice','Informations de profil enregistrées.');
            }

            return $this->render('LPSIOPlateformeBundle:Administration:modifier-mes-informations.html.twig', array('utilisateur' => $utilisateur, 'form' => $form->createView()));
        }
        else
        {
            return $this->redirectToRoute('lpsio_plateforme_login');
        }
    }

    public function modifierMonMotDePasseAction(Request $request)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            $utilisateur = $this->getUser();

            $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $utilisateur);

            $formBuilder = $this->createFormBuilder($utilisateur)
                ->add('password', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les mots de passe doivent correspondrent.',
                    'options' => array('attr' => array('class' => 'password-field')),
                    'required' => true,
                    'first_options' => array('label' => 'Nouveau mot de passe'),
                    'second_options' => array('label' => 'Répéter le mot de passe')))
                ->add('save', SubmitType::class, array('label' => 'Modifier mon mot de passe'));

            $form = $formBuilder->getForm();

            if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
            {
                $em = $this->getDoctrine()->getManager();

                $encoder = $this->container->get('security.password_encoder');

                $passwordEncoded = $encoder->encodePassword($utilisateur, $utilisateur->getPassword());

                $utilisateur->setPassword($passwordEncoded);

                $em->persist($utilisateur);

                $em->flush();

                $this->addFlash('notice','Nouveau mot de passe enregistré.');
            }

            return $this->render('LPSIOPlateformeBundle:Administration:modifier-mot-de-passe.html.twig', array('form' => $form->createView()));
        }
        else
        {
            return $this->redirectToRoute('lpsio_plateforme_login');
        }
    }

    public function aboutAction()
    {
        return $this->render('LPSIOPlateformeBundle:Default:about.html.twig');
    }

    public function alertesAction()
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            return $this->render('LPSIOPlateformeBundle:Default:alertes.html.twig');
        }
        else
        {
            return $this->redirectToRoute('lpsio_plateforme_login');
        }
    }

    public function offreAction($idOffre)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            $repositoryOffre = $this->getDoctrine()->getRepository('LPSIOPlateformeBundle:Offre');

            $offre = $repositoryOffre->find($idOffre);

            if(!$offre)
            {
                throw new NotFoundHttpException("L'offre ".$idOffre." n'existe pas.");
            }

            return $this->render('LPSIOPlateformeBundle:Default:offre.html.twig', array('offre' => $offre));
        }
        else
        {
            return $this->redirectToRoute('lpsio_plateforme_login');
        }
    }

    public function offresAction($page)
    {
        if($page < 1)
        {
            throw $this->createNotFoundException("La page ".$page." n'existe pas");
        }

        $offresParPage = $this->container->getParameter('offres_par_page');

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            $repositoryOffre = $this->getDoctrine()->getRepository('LPSIOPlateformeBundle:Offre');

            $offres = $repositoryOffre->getOffresParPage($page, $offresParPage);

            $nombreDePages = ceil(count($offres) / $offresParPage);

            if ($page > $nombreDePages)
            {
                throw $this->createNotFoundException("La page ".$page." n'existe pas.");
            }

            return $this->render('LPSIOPlateformeBundle:Default:offres.html.twig', array('offres' => $offres, 'nombreDePages' => $nombreDePages, 'page' => $page));
        }
        else
        {
            return $this->redirectToRoute('lpsio_plateforme_login');
        }
    }

    public function contactAction(Request $request)
    {
        $contact = new Contact();

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $contact);

        $formBuilder = $this->createFormBuilder($contact)
            ->add('nom', TextType::class, array('label' => 'Nom'))
            ->add('prenom', TextType::class, array('label' => 'Prénom'))
            ->add('courriel', EmailType::class, array('label' => 'Courriel'))
            ->add('message', TextareaType::class, array('label' => 'Message', 'required' => false))
            ->add('reset', ResetType::class, array('label' => 'Réinitialisation'))
            ->add('save', SubmitType::class, array('label' => 'Envoyer'));

        $form = $formBuilder->getForm();

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $contact->setDateContact(new \DateTime('now'));

            $em = $this->getDoctrine()->getManager();

            $em->persist($contact);

            $em->flush();

            $message = \Swift_Message::newInstance()
                ->setSubject("Votre demande de contact avec LPSIO")
                ->setFrom("contact@lpsio.fr")
                ->setTo($contact->getCourriel())
                ->setBody($this->renderView('LPSIOPlateformeBundle:Emails:message-contact.html.twig', array('contact' => $contact)),'text/html');

            $this->get('mailer')->send($message);

            $this->addFlash('notice','Votre message a été envoyé.');
        }

        return $this->render('LPSIOPlateformeBundle:Default:contact.html.twig', array('form' => $form->createView()));
    }

    public function inscriptionAction(Request $request)
    {
        $utilisateur = new Utilisateur();

        $builder = $this->createFormBuilder($utilisateur)
            ->add('nom', TextType::class, array('label' => 'Nom'))
            ->add('prenom', TextType::class, array('label' => 'Prénom'))
            ->add('adresse', TextType::class, array('label' => 'Adresse'))
            ->add('ville', TextType::class, array('label' => 'Ville'))
            ->add('pays', TextType::class, array('label' => 'Pays'))
            ->add('courriel', EmailType::class, array('label' => 'Courriel'))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array('label' => 'Mot de passe'),
                'second_options' => array('label' => 'Répétez le mot de passe')))
            ->add('reset', ResetType::class, array('label' => 'Réinitialisation'))
            ->add('save', SubmitType::class, array('label' => 'Valider'));

        $form = $builder->getForm();

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $utilisateur->setUsername($form['courriel']->getData());
            $utilisateur->setDateInscription(new \DateTime());
            $utilisateur->setSalt('');
            $utilisateur->setRoles(array('ROLE_USER'));

            $em = $this->getDoctrine()->getManager();

            $encoder = $this->container->get('security.password_encoder');

            $passwordEncoded = $encoder->encodePassword($utilisateur, $utilisateur->getPassword());

            $utilisateur->setPassword($passwordEncoded);

            $em->persist($utilisateur);

            $em->flush();

            $this->addFlash('notice','Inscription réussie !');
        }

        return $this->render('LPSIOPlateformeBundle:Default:inscription.html.twig', array('form' => $form->createView()));
    }

    public function modifierOffreAction($idOffre)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Accès non autorisé.');

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
        else
        {
            return $this->redirectToRoute('lpsio_plateforme_login');
        }
    }

    public function visualiserUtilisateursAction()
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Accès non autorisé.');

            $repositoryUtilisateur = $this->getDoctrine()->getRepository('LPSIOPlateformeBundle:Utilisateur');

            $utilisateurs = $repositoryUtilisateur->findBy(
                array(),
                array('nom' => 'ASC', 'prenom' => 'ASC'),
                null,
                null
            );

            return $this->render('LPSIOPlateformeBundle:Administration:visualiser-utilisateurs.html.twig', array('utilisateurs' => $utilisateurs));
        }
        else
        {
            return $this->redirectToRoute('lpsio_plateforme_login');
        }
    }

    public function visualiserOffresAction()
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Accès non autorisé.');

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
        else
        {
            return $this->redirectToRoute('lpsio_plateforme_login');
        }
    }

    public function creerOffreAction(Request $request)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Accès non autorisé.');

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
        else
        {
            return $this->redirectToRoute('lpsio_plateforme_login');
        }
    }


    public function supprimerOffreAction($idOffre)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Accès non autorisé.');

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
        else
        {
            return $this->redirectToRoute('lpsio_plateforme_login');
        }
    }

    public function modifierUtilisateurAction($idUtilisateur, Request $request)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Accès non autorisé.');

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
        else
        {
            return $this->redirectToRoute('lpsio_plateforme_login');
        }
    }

    public function supprimerUtilisateurAction($idUtilisateur)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Accès non autorisé.');

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
        else
        {
            return $this->redirectToRoute('lpsio_plateforme_login');
        }
    }
}