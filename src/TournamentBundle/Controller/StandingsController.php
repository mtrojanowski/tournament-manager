<?php
namespace TournamentBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use TournamentBundle\Repository\TeamsRepository;

/**
 * Class StandingsController
 * @package TournamentBundle\Controller
 * @Route("/tournaments/{tournamentId}/standings")
 */
class StandingsController extends TournamentManagerController
{
    /**
     * @Route("/", name="latest_standings")
     */
    public function latestStandingsAction(string $tournamentId)
    {
        $tournament = $this->getTournament($tournamentId);

        /** @var TeamsRepository $teamRepository */
        $teamRepository = $this->getTMRepository('Team');
        $teams = $teamRepository->getStandings($tournamentId);

        return $this->render('TournamentBundle:Standings:latest.html.twig', [
            'tournament' => $tournament,
            'teams' => $teams,
        ]);
    }
}