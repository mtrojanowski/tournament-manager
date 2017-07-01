<?php

namespace TournamentBundle\Controller;

use KMTStudio\TournamentBundle\Document\Tournament;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @package KMTStudio\TournamentBundle\Controller
 * @Route("/tournament")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('TournamentBundle:Default:index.html.twig');
    }

    /**
     * @Route("/create")
     */
    public function createAction()
    {
        $tournament = new Tournament();

        /** @var FormInterface $form */
        $form = $this->createFormBuilder($tournament)
            ->add('date', DateType::class)
            ->add('name', TextType::class)
            ->getForm();


        return $this->render('TournamentBundle:Default:new.html.twig', array('form' => $form->createView()));
    }
}
