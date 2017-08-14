<?php
namespace TournamentBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as Route;
use TournamentBundle\DMP\Scenarios;
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
    const TOURNAMENT_ID = "59918d28b018ad000c3aeb71";
    const TIME_FOR_ROUND = "+3 hours 15 minutes";

    /** @var Tournament */
    private $dmp;

    /**
     * @Route("/pairings", name="dmp_current_pairing")
     */
    public function currentPairingsAction()
    {
        $this->setDMP();
        return $this->pairings(
            'TournamentBundle:DMP:pairings.html.twig',
            null,
            $this->getMenuData('dmp_current_pairings')
        );
    }

    /**
     * @Route("/pairings/{round}", name="dmp_pairings_for_round")
     */
    public function pairingsForRoundAction(int $round)
    {
        $this->setDMP();
        return $this->pairings(
            'TournamentBundle:DMP:pairings.html.twig',
            $round,
            $this->getMenuData('dmp_pairings_for_round')
        );
    }

    /**
     * @Route("/projector_pairings", name="dmp_projector_pairings")
     */
    public function currentProjectorPairingsAction()
    {
        $this->setDMP();
        return $this->pairings(
            'TournamentBundle:DMP:projector_pairings.html.twig',
            null,
            $this->getMenuData('dmp_projector_pairings')
        );
    }

    /**
     * @Route("/time", name="dmp_time")
     */
    public function currentTimeForRound()
    {
        $this->setDMP();
        $round = $this->getCurrentRound();

        return $this->render('TournamentBundle:DMP:time.html.twig', [
            'round' => $round,
            'timeForRound' => self::TIME_FOR_ROUND,
            'menuData' => $this->getMenuData('dmp_time'),
        ]);
    }

    /**
     * @Route("/standings", name="dmp_latest_standings")
     */
    public function latestStandings()
    {
        $this->setDMP();

        /** @var TeamsRepository $teamRepository */
        $teamRepository = $this->getTMRepository('Team');
        $teams = $teamRepository->getStandings(self::TOURNAMENT_ID);

        return $this->render('TournamentBundle:DMP:latest.html.twig', [
            'tournament' => $this->dmp,
            'teams' => $teams,
            'menuData' => $this->getMenuData('dmp_latest_standings')
        ]);
    }

    /**
     * @Route("/standings/{roundNo}", name="dmp_standings_for_round")
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
            'menuData' => $this->getMenuData('dmp_standings_for_round')
        ]);
    }

    /**
     * @Route("/scenarios", name="dmp_scenarios")
     */
    public function scenariosForRound()
    {
        $this->setDMP();

        return $this->render('TournamentBundle:DMP:scenarios.html.twig', [
            'roundNo' => $this->dmp->getActiveRound(),
            'scenarios' => Scenarios::SCENARIOS[$this->dmp->getActiveRound()],
            'menuData' => $this->getMenuData('dmp_scenarios')
        ]);
    }

    /**
     * @Route("/vp", name="dmp_vp")
     */
    public function victoryPoints()
    {
        $this->setDMP();
        return $this->render('TournamentBundle:DMP:victory_points.html.twig', [
            'menuData' => $this->getMenuData('dmp_vp')
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
                    'route' => 'dmp_time',
                    'params' => []
                ],
                [
                    'name' => 'Latest standings',
                    'route' => 'dmp_latest_standings',
                    'params' => []
                ],
                [
                    'name' => 'Current pairings',
                    'route' => 'dmp_current_pairing',
                    'params' => []
                ],
                [
                    'name' => 'Scenarios',
                    'route' => 'dmp_scenarios',
                    'params' => []
                ],
                [
                    'name' => 'Victory Points',
                    'route' => 'dmp_vp',
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

        for ($i = 1; $i < $this->dmp->getActiveRound(); $i++) {
            $rounds[] = [
                'name' => 'Round ' . $i,
                'route' => 'dmp_pairings_for_round',
                'params' => ['round' => $i]
            ];
        }
        return $rounds;
    }


    private function getMenuRoundsStandings()
    {
        $rounds = [];

        for ($i = 1; $i < $this->dmp->getActiveRound(); $i++) {
            $rounds[] = [
                'name' => 'Round ' . $i,
                'route' => 'dmp_standings_for_round',
                'params' => ['roundNo' => $i]
            ];
        }
        return $rounds;
    }
}