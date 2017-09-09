<?php
namespace TournamentBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as Route;

use TournamentBundle\Document\Round;
use TournamentBundle\Document\Team;
use TournamentBundle\Document\Tournament;
use TournamentBundle\Repository\TeamsRepository;

/**
 * Class wsController
 * @package TournamentBundle\Controller
 * @Route("/t")
 */
class WSController extends TournamentManagerController
{
    const TOURNAMENT_ID = "59b2eb33129b6800706da701";
    const TIME_FOR_ROUND = "+2 hours 44 minutes";

    /** @var Tournament */
    private $ws;

    /**
     * @Route("", name="ws_current_pairing", host="ws.dmp2017.pl")
     */
    public function currentPairingsAction()
    {
        $this->setWS();
        return $this->pairings(
            'TournamentBundle:WS:pairings.html.twig',
            null,
            $this->getMenuData('ws_current_pairings')
        );
    }

    /**
     * @Route("/pairings/{round}", name="ws_pairings_for_round", host="ws.dmp2017.pl")
     */
    public function pairingsForRoundAction(int $round)
    {
        $this->setWS();
        return $this->pairings(
            'TournamentBundle:WS:pairings.html.twig',
            $round,
            $this->getMenuData('ws_pairings_for_round')
        );
    }

    /**
     * @Route("/projector_pairings", name="ws_projector_pairings", host="ws.dmp2017.pl")
     */
    public function currentProjectorPairingsAction()
    {
        $this->setWS();
        return $this->pairings(
            'TournamentBundle:WS:projector_pairings.html.twig',
            null,
            $this->getMenuData('ws_projector_pairings')
        );
    }

    /**
     * @Route("/time", name="ws_time", host="ws.dmp2017.pl")
     */
    public function currentTimeForRound()
    {
        $this->setWS();
        $round = $this->getCurrentRound();

        return $this->render('TournamentBundle:WS:time.html.twig', [
            'round' => $round,
            'timeForRound' => self::TIME_FOR_ROUND,
            'menuData' => $this->getMenuData('ws_time'),
        ]);
    }

    /**
     * @Route("/standings", name="ws_latest_standings", host="ws.dmp2017.pl")
     */
    public function latestStandings()
    {
        $this->setWS();

        /** @var TeamsRepository $teamRepository */
        $teamRepository = $this->getTMRepository('Team');
        $teams = $teamRepository->getStandings(self::TOURNAMENT_ID);

        return $this->render('TournamentBundle:WS:latest.html.twig', [
            'tournament' => $this->ws,
            'teams' => $teams,
            'menuData' => $this->getMenuData('ws_latest_standings')
        ]);
    }

    /**
     * @Route("/standings/{roundNo}", name="ws_standings_for_round", host="ws.dmp2017.pl")
     */
    public function standingsAfterRound(int $roundNo)
    {
        $this->setWS();

        if ($roundNo >= $this->ws->getActiveRound() && $this->ws->getStatus() != 'FINISHED') {
            throw $this->createNotFoundException('Round not yet finished. Standings will be available once round is finished.');
        }

        /** @var TeamsRepository $teamRepository */
        $teamRepository = $this->getTMRepository('Team');
        $teams = $teamRepository->getStandings($this->ws->getId());

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

        return $this->render('TournamentBundle:WS:round.html.twig', [
            'tournament' => $this->ws,
            'roundNo' => $roundNo,
            'teams' => $teams,
            'menuData' => $this->getMenuData('ws_standings_for_round')
        ]);
    }

    /**
     * @Route("/vp", name="ws_vp", host="ws.dmp2017.pl")
     */
    public function victoryPoints()
    {
        $this->setWS();
        return $this->render('TournamentBundle:WS:victory_points.html.twig', [
            'menuData' => $this->getMenuData('ws_vp')
        ]);
    }

    /**
     * @Route("/final-standings", name="ws_final_standings", host="ws.dmp2017.pl")
     */
    public function finalStandings()
    {
        $this->setWS();

        /** @var TeamsRepository $teamRepository */
        $teamRepository = $this->getTMRepository('Team');
        $teams = $teamRepository->getFinalStandings($this->ws->getId());

        return $this->render('TournamentBundle:Standings:final.html.twig', [
            'tournament' => $this->ws,
            'teams' => $teams
        ]);
    }

    private function setWS()
    {
        $this->ws = $this->getTournament(self::TOURNAMENT_ID);
    }

    private function getCurrentRound():?Round
    {
        if ($this->ws->getActiveRound()) {
            return $this->getRound($this->ws->getActiveRound());
        }

        return null;
    }

    private function getRound(int $roundNo):Round
    {
        /** @var Round $round */
        $round = $this->getTMRepository('Round')
            ->findOneBy(['tournamentId' => self::TOURNAMENT_ID, 'roundNo' => $roundNo]);

        return $round;
    }

    private function pairings(string $templateName, int $roundNo = null, $menuData)
    {
        if (empty($roundNo)) {
            $round = $this->getCurrentRound();
        } else {
            $round = $this->getRound($roundNo);
        }

        return $this->render(
            $templateName,
            [
                'pairings' => $round->getTables(),
                'roundNo' => $round->getRoundNo(),
                'roundVerified' => $round->getVerified(),
                'numPairings' => count($round->getTables()),
                'menuData' => $menuData
            ]);
    }

    private function getMenuData($active)
    {
        return [
            'menuList' => [
                [
                    'name' => 'Time',
                    'route' => 'ws_time',
                    'params' => []
                ],
                [
                    'name' => 'Latest standings',
                    'route' => 'ws_latest_standings',
                    'params' => []
                ],
                [
                    'name' => 'Current pairings',
                    'route' => 'ws_current_pairing',
                    'params' => []
                ],
                [
                    'name' => 'Victory Points',
                    'route' => 'ws_vp',
                    'params' => []
                ],
            ],
            'menuRoundsPairings' => $this->getMenuRoundsPairings(),
            'menuRoundsStandings' => $this->getMenuRoundsStandings(),

            'active' => $active,
        ];
    }

    private function getMenuRoundsPairings()
    {
        $rounds = [];

        for ($i = 1; $i < $this->ws->getActiveRound(); $i++) {
            $rounds[] = [
                'name' => 'Round ' . $i,
                'route' => 'ws_pairings_for_round',
                'params' => ['round' => $i]
            ];
        }
        return $rounds;
    }


    private function getMenuRoundsStandings()
    {
        $rounds = [];

        for ($i = 1; $i < $this->ws->getActiveRound(); $i++) {
            $rounds[] = [
                'name' => 'Round ' . $i,
                'route' => 'ws_standings_for_round',
                'params' => ['roundNo' => $i]
            ];
        }
        return $rounds;
    }
}