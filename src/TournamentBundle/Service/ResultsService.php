<?php
namespace TournamentBundle\Service;


use TournamentBundle\Document\Table;

class ResultsService
{
    const BATTLE_POINTS_MAX_CAP = 65;
    const BATTLE_POINTS_MIN_CAP = 35;

    public function setTableResult(Table $table, array $resultsData):Table
    {
        $table->getTeam1()->setMatchPoints($resultsData['team1MatchPoints']);
        $table->getTeam1()->setBattlePoints($this->countBattlePoints($resultsData['team1MatchPoints']));
        $table->getTeam1()->setPenalty($resultsData['team1Penalty']);

        if ($table->getTeam2() !== null) {
            $table->getTeam2()->setMatchPoints($resultsData['team2MatchPoints']);
            $table->getTeam2()->setBattlePoints($this->countBattlePoints($resultsData['team2MatchPoints']));
            $table->getTeam2()->setPenalty($resultsData['team2Penalty']);
        }

        return $table;
    }

    private function countBattlePoints(int $matchPoints):int
    {
        return $matchPoints > self::BATTLE_POINTS_MAX_CAP ? self::BATTLE_POINTS_MAX_CAP :
            ($matchPoints < self::BATTLE_POINTS_MIN_CAP ? self::BATTLE_POINTS_MIN_CAP : $matchPoints);
    }
}