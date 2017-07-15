<?php
namespace TournamentBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as Route;
use TournamentBundle\Document\Round;

/**
 * Class DMPController
 * @package TournamentBundle\Controller
 * @Route("/dmp")
 */
class DMPController extends TournamentManagerController
{
    const TOURNAMENT_ID = "595bfafe2e783a000e181573";

    /**
     * @Route("/pairings")
     */
    public function currentPairingsAction()
    {
        $tournament = $this->getTournament(self::TOURNAMENT_ID);
        /** @var Round $round */
        $round = $this->getTMRepository('Round')->findOneBy(['tournamentId' => self::TOURNAMENT_ID, 'roundNo' => $tournament->getActiveRound()]);

        return $this->render(
            'TournamentBundle:DMP:pairings.html.twig',
            ['pairings' => $round->getTables(), 'roundNo' => $tournament->getActiveRound(), 'roundVerified' => $round->getVerified()]);
    }
}