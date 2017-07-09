<?php

namespace TournamentBundle\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use TournamentBundle\Document\Tournament;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @package TournamentBundle\Controller
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
    public function createAction(Request $request)
    {
        $tournament = new Tournament();
        $tournament->setDate(new \DateTime());

        /** @var FormInterface $form */
        $form = $this->createFormBuilder($tournament)
            ->add('date', DateType::class)
            ->add('name', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Add tournament'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tournament = $form->getData();
            /** @var DocumentManager $documentManager */
            $documentManager = $this->get('doctrine_mongodb')->getManager();
            $documentManager->persist($tournament);
            $documentManager->flush();

            $this->addFlash('info', 'Tournament added successfully');
        }

        return $this->render('TournamentBundle:Default:new.html.twig', array('form' => $form->createView()));
    }
}
