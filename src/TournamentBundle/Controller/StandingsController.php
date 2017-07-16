<?php
namespace TournamentBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as Route;

/**
 * Class StandingsController
 * @package TournamentBundle\Controller
 * @Route("/tournaments/{tournamentId}/standings
 */
class StandingsController extends TournamentManagerController
{
    /**
     * @Route("/", name="latest_standings")
     */
    public function latestStandingsAction(string $tournamentId)
    {
        $tournament = $this->getTournament($tournamentId);

        return $this->render('TournamentBundle:Standings:latest.html.twig', [
            'teams' => $teams,
        ]);
    }
}