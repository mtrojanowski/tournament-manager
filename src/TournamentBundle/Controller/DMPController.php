<?php
namespace TournamentBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as Route;
use TournamentBundle\Document\Round;
use TournamentBundle\Document\Tournament;

/**
 * Class DMPController
 * @package TournamentBundle\Controller
 * @Route("/dmp")
 */
class DMPController extends TournamentManagerController
{
    const TOURNAMENT_ID = "597b3fe3fe9c9c000d0a3c01";
    const TIME_FOR_ROUND = "+3 hours 15 minutes";

    /** @var Tournament */
    private $dmp;

    /**
     * @Route("/pairings")
     */
    public function currentPairingsAction()
    {
        return $this->pairings('TournamentBundle:DMP:pairings.html.twig');
    }

    /**
     * @Route("/projector_pairings")
     */
    public function currentProjectorPairingsAction()
    {
        return $this->pairings('TournamentBundle:DMP:projector_pairings.html.twig');
    }

    /**
     * @Route("/time")
     */
    public function currentTimeForRound()
    {
        $this->setDMP();
        $round = $this->getCurrentRound();

        return $this->render('TournamentBundle:DMP:time.html.twig', ['round' => $round, 'timeForRound' => self::TIME_FOR_ROUND]);
    }

    private function setDMP()
    {
        $this->dmp = $this->getTournament(self::TOURNAMENT_ID);
    }

    private function getCurrentRound():Round
    {
        /** @var Round $round */
        $round = $this->getTMRepository('Round')
            ->findOneBy(['tournamentId' => self::TOURNAMENT_ID, 'roundNo' => $this->dmp->getActiveRound()]);

        return $round;
    }

    private function pairings(string $templateName)
    {
        $this->setDMP();
        $round = $this->getCurrentRound();

        return $this->render(
            $templateName,
            [
                'pairings' => $round->getTables(),
                'roundNo' => $this->dmp->getActiveRound(),
                'roundVerified' => $round->getVerified(),
                'numPairings' => count($round->getTables())
            ]);
    }
}