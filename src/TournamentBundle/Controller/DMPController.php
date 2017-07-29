<?php
namespace TournamentBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as Route;
use TournamentBundle\Document\Round;
use TournamentBundle\Document\Team;
use TournamentBundle\Document\Tournament;
use TournamentBundle\Repository\TeamsRepository;

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
     * @Route("/pairings/{round}")
     */
    public function pairingsForRoundAction(int $round)
    {
        return $this->pairings('TournamentBundle:DMP:pairings.html.twig', $round);
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

    /**
     * @Route("/standings/{roundNo}")
     */
    public function standingsAfterRound(int $roundNo)
    {
        $this->setDMP();

        if ($roundNo >= $this->dmp->getActiveRound() && $this->dmp->getStatus() != 'FINISHED') {
            throw $this->createNotFoundException('Round not yet finished. Standings will be available once round is finished.');
        }

        /** @var TeamsRepository $teamRepository */
        $teamRepository = $this->getTMRepository('Team');
        $teams = $teamRepository->getStandings($this->dmp->getId());

        foreach ($teams as $team) {
            /** @var Team $team */
            $team->recalculateResultsForRound($roundNo);
        }

        usort($teams, function(Team $team1, Team $team2) {
           if ($team1->getBattlePoints() > $team2->getBattlePoints()) {
               return 1;
           }

           if ($team1->getBattlePoints() == $team2->getBattlePoints()) {
               if ($team1->getMatchPoints() > $team2->getMatchPoints()) {
                   return 1;
               }

               if ($team1->getMatchPoints() == $team2->getMatchPoints()) {
                   return 0;
               }
           }

            return -1;
        });

        $teams = array_reverse($teams);

        return $this->render('TournamentBundle:Standings:round.html.twig', [
            'tournament' => $this->dmp,
            'roundNo' => $roundNo,
            'teams' => $teams,
        ]);
    }

    private function setDMP()
    {
        $this->dmp = $this->getTournament(self::TOURNAMENT_ID);
    }

    private function getCurrentRound():Round
    {
        return $this->getRound($this->dmp->getActiveRound());
    }

    private function getRound(int $roundNo):Round
    {
        /** @var Round $round */
        $round = $this->getTMRepository('Round')
            ->findOneBy(['tournamentId' => self::TOURNAMENT_ID, 'roundNo' => $roundNo]);

        return $round;
    }

    private function pairings(string $templateName, int $roundNo = null)
    {
        $this->setDMP();
        if (empty($roundNo)) {
            $round = $this->getCurrentRound();
        } else {
            $round = $this->getRound($roundNo);
        }

        return $this->render(
            $templateName,
            [
                'pairings' => $round->getTables(),
                'roundNo' => $roundNo,
                'roundVerified' => $round->getVerified(),
                'numPairings' => count($round->getTables())
            ]);
    }
}