<?php
namespace TournamentBundle\Controller;

use TournamentBundle\Document\Round;
use TournamentBundle\Document\Table;
use TournamentBundle\Document\Team;
use TournamentBundle\Form\SwitchTableType;
use TournamentBundle\Service\PairingService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


/**
 * Class TournamentController
 * @package TournamentBundle\Controller
 * @Route("/tournaments/{tournamentId}")
 */
class TournamentController extends TournamentManagerController
{
    /**
     * @Route("/start", name="start_tournament")
     */
    public function startTournamentAction(string $tournamentId)
    {
        $tournament = $this->getTournament($tournamentId);

        $dm = $this->getDocumentManager();

        $tournament->setStatus("STARTED");
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
        $round->setRoundNo(1);
        $round->setTournamentId($tournamentId);

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
        /** @var Round $round */
        $round = $this->getTMRepository('Round')->findOneBy(['tournamentId' => $tournamentId, 'roundNo' => $roundNo]);

        $switchForms = [];
        foreach ($round->getTables() as $table) {
            /** @var Table $table */
            $switchForms[$table->getTableNo()][1] = $this->createForm(SwitchTableType::class, ['sourceTableNo' => $table->getTableNo(), 'sourceTeamNo' => 1])->createView();
            $switchForms[$table->getTableNo()][2] = $this->createForm(SwitchTableType::class, ['sourceTableNo' => $table->getTableNo(), 'sourceTeamNo' => 2])->createView();
        }

        return $this->render('TournamentBundle:Tournament:verify_round.html.twig', ['tournament' => $tournament, 'round' => $round, 'switchForms' => $switchForms]);
    }
}