<?php
namespace TournamentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use TournamentBundle\Document\Result;
use TournamentBundle\Document\Round;
use TournamentBundle\Document\Table;
use TournamentBundle\Document\Team;
use TournamentBundle\Form\SwitchTableType;
use TournamentBundle\Form\TableResultType;
use TournamentBundle\Repository\TeamsRepository;
use TournamentBundle\Service\PairingService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use TournamentBundle\Service\ResultsService;


/**
 * Class TournamentController
 * @package TournamentBundle\Controller
 * @Route("/tournaments/{tournamentId}")
 */
class TournamentController extends TournamentManagerController
{
    const BYE = 'bye';

    /**
     * @Route("/start", name="start_tournament")
     */
    public function startTournamentAction(string $tournamentId)
    {
        $tournament = $this->getTournament($tournamentId);

        $dm = $this->getDocumentManager();

        $tournament->setStatus('STARTED');
        $tournament->setActiveRound(1);
        $dm->persist($tournament);

        $teams = $this->getTMRepository('Team')->findBy(['tournamentId' => $tournamentId, 'confirmedDay1' => true]);

        shuffle($teams);
        shuffle($teams);
        shuffle($teams);

        /** @var PairingService $pairingService */
        $pairingService = $this->get('pairingService');
        $pairings = $pairingService->createPairing($teams, true);

        $round = new Round();
        $round
            ->setRoundNo(1)
            ->setTournamentId($tournamentId)
            ->setVerified(false)
            ->setStarted(false);

        foreach ($pairings as $table) {
            $round->addTable($table);
        }

        $dm->persist($round);
        $dm->flush();

        $this->addFlash('info', 'Pairings for round one randomised. Please check if everything is ok.');

        return $this->redirectToRoute('verify_round', ['tournamentId' => $tournamentId, 'roundNo' => 1]);
    }

    /**
     * @Route("/rounds/{roundNo}/verify", name="verify_round")
     */
    public function verifyRoundAction(string $tournamentId, int $roundNo)
    {
        $tournament = $this->getTournament($tournamentId);
        $round = $this->getRound($tournamentId, $roundNo);

        $switchTableAction = $this->generateUrl('switch_teams', ['tournamentId' => $tournamentId, 'roundNo' => $roundNo]);

        $switchForms = [];
        foreach ($round->getTables() as $table) {
            /** @var Table $table */
            $switchForms[$table->getTableNo()][1] = $this->createForm(
                SwitchTableType::class,
                ['sourceTableNo' => $table->getTableNo(), 'sourceTeamNo' => 1],
                ['action' => $switchTableAction]
            )->createView();
            $switchForms[$table->getTableNo()][2] = $this->createForm(
                SwitchTableType::class,
                ['sourceTableNo' => $table->getTableNo(), 'sourceTeamNo' => 2],
                ['action' => $switchTableAction]
            )->createView();
        }

        /** @var TeamsRepository $teamsRepository */
        $teamsRepository = $this->getTMRepository('Team');
        $teams = $teamsRepository->getTeamsPlaying($tournamentId, $roundNo > 5);

        $teamsIndexed = [];

        foreach ($teams as $team) {
            /** @var Team $team */
            $teamsIndexed[$team->getId()] = $team;
        }

        return $this->render('TournamentBundle:Tournament:verify_round.html.twig', ['tournament' => $tournament, 'round' => $round, 'switchForms' => $switchForms, 'teams' => $teamsIndexed]);
    }

    /**
     * @Route("/rounds/{roundNo}/verified", name="round_verified")
     */
    public function setRoundVerifiedAction(string $tournamentId, int $roundNo)
    {
        $tournament = $this->getTournament($tournamentId);
        $round = $this->getRound($tournamentId, $roundNo);

        $round->setVerified(true);

        $dm = $this->getDocumentManager();
        $dm->persist($round);
        $dm->flush();

        $this->addFlash('info', sprintf('Pairings for round %s verified', $roundNo));

        return $this->redirectToRoute('verify_round', ['tournamentId' => $tournamentId, 'roundNo' => $roundNo]);
    }

    /**
     * @Route("/rounds/{roundNo}/switch", name="switch_teams", methods="POST")
     */
    public function switchTeams(string $tournamentId, int $roundNo, Request $request)
    {
        $tournament = $this->getTournament($tournamentId);
        $round = $this->getRound($tournamentId, $roundNo);

        if ($round->getStarted()) {
            $this->addFlash('warning', 'This round has already started, yo can\'t switch teams in a started round.');

            return $this->redirectToRoute('verify_round', ['tournamentId' => $tournamentId, 'roundNo' => $roundNo]);
        }

        $form = $this->createForm(SwitchTableType::class);
        $form->handleRequest($request);
        $data = $form->getData();

        /** @var PairingService $pairingService */
        $pairingService = $this->get('pairingService');
        $round->setTables($pairingService->switchTeams($round->getTables(), $data));

        $dm = $this->getDocumentManager();
        $dm->persist($round);
        $dm->flush();

        $this->addFlash('info', 'Teams switched.');

        return $this->redirectToRoute('verify_round', ['tournamentId' => $tournamentId, 'roundNo' => $roundNo]);
    }

    /**
     * @Route("/rounds/{roundNo}/start", name="start_round")
     */
    public function startRoundAction(string $tournamentId, int $roundNo)
    {
        $tournament = $this->getTournament($tournamentId);
        $round = $this->getRound($tournamentId, $roundNo);

        $round
            ->setStarted(true)
            ->setStartedAt(time());

        $dm = $this->getDocumentManager();
        $dm->persist($round);
        $dm->flush();

        $this->addFlash('info', sprintf('Round %s started.', $roundNo));

        return $this->redirectToRoute('results_for_round', ['tournamentId' => $tournamentId, 'roundNo' => $roundNo]);
    }

    /**
     * @Route("/rounds/{roundNo}/results", name="results_for_round")
     */
    public function resultsForRoundAction(string $tournamentId, int $roundNo)
    {
        $tournament = $this->getTournament($tournamentId);
        $round = $this->getRound($tournamentId, $roundNo);
        $canFinishRound = true;
        $resultForms = [];

        foreach ($round->getTables() as $table) {
            /** @var Table $table */
            $resultForms[$table->getTableNo()] = $this->createForm(
                TableResultType::class,
                array_merge($table->getResultsData(), ['isBye' => $table->getTeam2() == null]),
                [
                    'action' => $this->generateUrl('set_table_result', ['tournamentId' => $tournamentId, 'roundNo' => $roundNo]),
                ]
            )->createView();

            if ($canFinishRound && !$table->resultsSet()) {
                $canFinishRound = false;
            }
        }

        return $this->render(
            'TournamentBundle:Tournament:results.html.twig',
            ['tournament' => $tournament, 'round' => $round, 'resultForms' => $resultForms, 'canFinishRound' => $canFinishRound]
        );
    }

    /**
     * @Route("/rounds/{roundNo}/tableResult", name="set_table_result", methods="POST")
     */
    public function setTableResults(string $tournamentId, int $roundNo, Request $request)
    {
        $tournament = $this->getTournament($tournamentId);
        $round = $this->getRound($tournamentId, $roundNo);

        /** @var ResultsService $resultsService */
        $resultsService = $this->get('resultsService');

        $resultsForm = $this->createForm(TableResultType::class);
        $resultsForm->handleRequest($request);
        $resultsData = $resultsForm->getData();

        $updatedTable = $resultsService->setTableResult($round->getTable($resultsData['tableNo']), $resultsData);
        $round->setTable($resultsData['tableNo'], $updatedTable);

        /** @var Team $team1 */
        $team1 = $this->getTMRepository('Team')->find($updatedTable->getTeam1()->getTeamId());
//        $team1
//            ->setBattlePoints($team1->getBattlePoints() + $updatedTable->getTeam1()->getBattlePoints())
//            ->setMatchPoints($team1->getMatchPoints() + $updatedTable->getTeam1()->getMatchPoints())
//            ->setPenaltyPoints($team1->getPenaltyPoints() + $updatedTable->getTeam1()->getPenalty())
//            ->setFinalBattlePoints($team1->getFinalBattlePoints() + $updatedTable->getTeam1()->getBattlePoints() - $updatedTable->getTeam1()->getPenalty());

        $resultForTeam1 = new Result();
        $resultForTeam1
            ->setRoundNo($roundNo)
            ->setMatchPoints($updatedTable->getTeam1()->getMatchPoints())
            ->setBattlePoints($updatedTable->getTeam1()->getBattlePoints())
            ->setPenalty($updatedTable->getTeam1()->getPenalty());

        if ($updatedTable->getTeam2() !== null) {
            $resultForTeam1
                ->setOpponentId($updatedTable->getTeam2()->getTeamId())
                ->setOpponentName($updatedTable->getTeam2()->getTeamName())
                ->setOpponentCountry($updatedTable->getTeam2()->getTeamCountry());
        } else {
            $resultForTeam1
                ->setOpponentId(self::BYE)
                ->setOpponentName(self::BYE);
        }

        $team1->setResultForRound($roundNo, $resultForTeam1);

        $dm = $this->getDocumentManager();
        $dm->persist($round);
        $dm->persist($team1);

        if ($updatedTable->getTeam2() !== null) {
            /** @var Team $team2 */
            $team2 = $this->getTMRepository('Team')->find($updatedTable->getTeam2()->getTeamId());
//            $team2
//                ->setBattlePoints($team2->getBattlePoints() + $updatedTable->getTeam2()->getBattlePoints())
//                ->setMatchPoints($team2->getMatchPoints() + $updatedTable->getTeam2()->getMatchPoints())
//                ->setPenaltyPoints($team2->getPenaltyPoints() + $updatedTable->getTeam2()->getPenalty())
//                ->setFinalBattlePoints($team2->getFinalBattlePoints() + $updatedTable->getTeam2()->getBattlePoints() - $updatedTable->getTeam2()->getPenalty());

            $resultForTeam2 = new Result();
            $resultForTeam2
                ->setRoundNo($roundNo)
                ->setMatchPoints($updatedTable->getTeam2()->getMatchPoints())
                ->setBattlePoints($updatedTable->getTeam2()->getBattlePoints())
                ->setPenalty($updatedTable->getTeam2()->getPenalty())
                ->setOpponentId($updatedTable->getTeam1()->getTeamId())
                ->setOpponentName($updatedTable->getTeam1()->getTeamName())
                ->setOpponentCountry($updatedTable->getTeam1()->getTeamCountry());

            $team2->setResultForRound($roundNo, $resultForTeam2);
            $dm->persist($team2);
        }

        $dm->flush();

        $this->addFlash('info', sprintf('Results for table %s set', $resultsData['tableNo']));

        return $this->redirectToRoute('results_for_round', ['tournamentId' => $tournamentId, 'roundNo' => $roundNo]);
    }

    /**
     * @Route("/round/{roundNo}/finish", name="finish_round")
     */
    public function finishRound(string $tournamentId, int $roundNo)
    {
        $tournament = $this->getTournament($tournamentId);
        $round = $this->getRound($tournamentId, $roundNo);

        $nextRoundNo = $roundNo + 1;

        $tournament->setActiveRound($nextRoundNo);
        $round->setFinishedAt(time());

        /** @var TeamsRepository $teamsRepository */
        $teamsRepository = $this->getTMRepository('Team');
        $teams = $teamsRepository->getTeamsPlaying($tournamentId, $nextRoundNo > 4);

        /** @var PairingService $pairingsService */
        $pairingsService = $this->get('pairingService');
        $pairings = $pairingsService->createPairing($teams, false);

        $nextRound = new Round();
        $nextRound
            ->setRoundNo($nextRoundNo)
            ->setTournamentId($tournamentId)
            ->setVerified(false)
            ->setStarted(false);

        foreach ($pairings as $table) {
            $nextRound->addTable($table);
        }

        $dm = $this->getDocumentManager();
        $dm->persist($tournament);
        $dm->persist($round);
        $dm->persist($nextRound);

        $dm->flush();

        $this->addFlash('info', sprintf('Round %s finished. Pairings for round %s prepared. Please verify pairings.', $roundNo, $nextRoundNo));

        return $this->redirectToRoute('verify_round', ['tournamentId' => $tournamentId, 'roundNo' => $nextRoundNo]);
    }

    /**
     * @Route("/finish", name="finish_tournament")
     */
    public function finishTournament(string $tournamentId)
    {
        $tournament = $this->getTournament($tournamentId);
        $tournament->setStatus('FINISHED');

        $dm = $this->getDocumentManager();
        $dm->persist($tournament);
        $dm->flush();

        $this->addFlash('info', 'Tournament finished');

        return $this->redirectToRoute('final_standings', ['tournamentId' => $tournamentId]);
    }

    private function getRound(string $tournamentId, int $roundNo):Round
    {
        /** @var Round $round */
        $round = $this->getTMRepository('Round')->findOneBy(['tournamentId' => $tournamentId, 'roundNo' => $roundNo]);
        return $round;
    }
}