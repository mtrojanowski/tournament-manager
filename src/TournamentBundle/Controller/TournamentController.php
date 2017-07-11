<?php
namespace TournamentBundle\Controller;

use TournamentBundle\Document\Team;

/**
 * Class TournamentController
 * @package TournamentBundle\Controller
 * @Route("/tournaments/{tournamentId}")
 */
class TournamentController extends TournamentManagerController
{
    /**
     * @Route("/start")
     */
    public function startTournamentAction($tournamentId)
    {
        $tournament = $this->getTournament($tournamentId);

        $tournament->setStatus("STARTED");
        $tournament->setActiveRound(1);

        $teams = $this->getTMRepository('Team')->findBy(['tournamentId' => $tournamentId, 'confirmedDay1' => true]);

        shuffle($teams);
        shuffle($teams);
        shuffle($teams);

        foreach ($teams as $i => $team) {
            /** @var Team $team */

            if ($i % 2 == 1) {
                continue;
            }

            /** @var Team $opponent */
            $opponent = $teams[$i + 1];

            if ($team->getClub() == $opponent->getClub() || ($team->getCountry() != 'Poland' && $team->getCountry() == $opponent->getCountry())) {
                $nextOpponent = $teams[$i + 2];
                $teams[$i + 1] = $nextOpponent;
                $teams[$i + 2] = $opponent;
            }
        }
    }
}