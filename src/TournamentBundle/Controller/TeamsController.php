<?php

namespace TournamentBundle\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use TournamentBundle\Document\Team;
use TournamentBundle\Document\Tournament;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use TournamentBundle\Form\TeamType;

/**
 * Class TeamsController
 * @package TournamentBundle\Controller
 * @Route("/tournament/{tournamentId}/teams")
 */
class TeamsController extends TournamentManagerController
{
    private function getTeam($tournamentId, $teamId):Team
    {
        /** @var Team $team */
        $team = $this->getTMRepository('Team')->findOneBy(['id' => $teamId, 'tournamentId' => $tournamentId]);

        if (is_null($team)) {
            throw $this->createNotFoundException('Team not found.');
        }

        return $team;
    }

    /**
     * @Route("/", name="list_with_add")
     */
    public function indexAction($tournamentId)
    {
        $tournament = $this->getTournament($tournamentId);

        $team = new Team();
        $team->setTournamentId($tournamentId);

        /** @var FormInterface $form */
        $form = $this->createForm(
            TeamType::class,
            $team,
            [
                'action' => $this->generateUrl('add_team', ['tournamentId' => $tournamentId])
            ]
        );

        $teams = $this->getTMRepository('Team')->findByTournamentId($tournamentId);

        /** @var FormInterface $startTournamentForm */
        $startTournamentForm = $this->createFormBuilder()
            ->add('tournamentId', HiddenType::class, ['data' => $tournamentId])
            ->add('start', SubmitType::class, ['label' => 'Start tournament'])
            ->setAction('')
            ->getForm();

        return $this->render('TournamentBundle:Teams:index.html.twig', [
            'tournamentName' => $tournament->getName(),
            'newTeamForm' => $form->createView(),
            'teams' => $teams,
            'startTournamentForm' => $startTournamentForm->createView()
        ]);
    }

    /**
     * @Route("/add", name="add_team")
     */
    public function addTeamAction($tournamentId, Request $request)
    {
        $tournament = $this->getTournament($tournamentId);

        $form = $this->createForm(TeamType::class, new Team());
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            $this->redirectToRoute('list_with_add', ['tournamentId' => $tournamentId]);
        }

        if ($form->isValid()) {
            /** @var Team $team */
            $team = $form->getData();

            $dm = $this->getDocumentManager();
            $dm->persist($form->getData());
            $dm->flush();

            $this->addFlash('info', sprintf('Team %s added to the tournament.', $team->getName()));
        }

        return $this->redirectToRoute('list_with_add', ['tournamentId' => $tournamentId]);
    }

    /**
     * @Route("/{teamId}/remove", name="remove_team")
     */
    public function removeTeamAction($tournamentId, $teamId)
    {
        $tournament = $this->getTournament($tournamentId);

        $team = $this->getTeam($tournamentId, $teamId);

        $dm = $this->getDocumentManager();
        $dm->remove($team);
        $dm->flush();

        $this->addFlash('info', sprintf('Team %s removed successfully.', $team->getName()));

        return $this->redirectToRoute('list_with_add', ['tournamentId' => $tournamentId]);
    }

    /**
     * @Route("/{teamId}/confirm/{day}", name="confirm_presence")
     * */
    public function confirmPresenceActon($tournamentId, $teamId, $day)
    {
        $tournament = $this->getTournament($tournamentId);

        if ($day != 1 && $day != 2) {
            throw $this->createNotFoundException('Please confirm first or second day.');
        }

        $team = $this->getTeam($tournamentId, $teamId);

        switch ($day) {
            case 1:
                $team->setConfirmedDay1(true);
                break;
            case 2:
                $team->setConfirmedDay2(true);
                break;
            default:
                break;
        }

        $dm = $this->getDocumentManager();
        $dm->persist($team);
        $dm->flush();

        $this->addFlash('info', sprintf('%s presence on day %s confirmed', $team->getName(), $day));

        return $this->redirectToRoute('list_with_add', ['tournamentId' => $tournamentId]);
    }
}
