<?php

namespace TournamentBundle\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use TournamentBundle\Document\Team;
use TournamentBundle\Document\Tournament;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TeamsController
 * @package TournamentBundle\Controller
 * @Route("/tournament/{tournamentId}/teams")
 */
class TeamsController extends Controller
{

    private function getTournament($id):Tournament
    {
        /** @var DocumentRepository $repo */
        $repo = $this->get('doctrine_mongodb')->getRepository('TournamentBundle:Tournament');
        /** @var Tournament $tournament */
        $tournament = $repo->find($id);

        return $tournament;
    }
    /**
     * @Route("/", name="list_with_add")
     */
    public function indexAction($tournamentId)
    {
        $tournament = $this->getTournament($tournamentId);

        /** @var FormInterface $form */
        $form = $this->createFormBuilder(new Team())
            ->add('name', TextType::class)
            ->add('country', TextType::class, ['data' => 'Poland'])
            ->add('club', TextType::class)
            ->add('tournamentId', HiddenType::class, ['data' => $tournamentId])
            ->add('save', SubmitType::class, ['label' => 'Add team'])
            ->setAction($this->generateUrl('add_team', ['tournamentId' => $tournamentId]))
            ->getForm();

        return $this->render('TournamentBundle:Teams:index.html.twig', [
            'tournamentName' => $tournament->getName(),
            'newTeamForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/add", name="add_team")
     */
    public function addTeamAction($tournamentId, Request $request)
    {
        $tournament = $this->getTournament($tournamentId);


        return $this->render('TournamentBundle:Default:new.html.twig', []);
    }
}
