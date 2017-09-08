<?php
namespace TournamentBundle\Service;


use TournamentBundle\Document\Table;

class ResultsService
{
    const BATTLE_POINTS_MAX_CAP = 65;
    const BATTLE_POINTS_MIN_CAP = 35;

    public function setTableSingleResult(Table $table, array $resultsData):Table
    {
        $result = $this->countSingleBattlePoints($resultsData);

        $table->getTeam1()->setMatchPoints($resultsData['team1MatchPoints']);
        $table->getTeam1()->setBattlePoints($result['team1']);
        $table->getTeam1()->setScenario($resultsData['team1Scenario']);
        $table->getTeam1()->setPenalty($resultsData['team1Penalty']);

        if ($table->getTeam2() !== null) {
            $table->getTeam2()->setMatchPoints($resultsData['team2MatchPoints']);
            $table->getTeam2()->setBattlePoints($result['team2']);
            $table->getTeam2()->setScenario($resultsData['team2Scenario']);
            $table->getTeam2()->setPenalty($resultsData['team2Penalty']);
        }

        return $table;
    }

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

    private function countSingleBattlePoints(array $resultsData):array
    {
        $difference = $resultsData['team1MatchPoints'] - $resultsData['team2MatchPoints'];

        $points = [
            'team1' => 10,
            'team2' => 10,
        ];

        if ($difference >= 3151) {
            $points['team1'] = 17;
        } else if ($difference > 2250) {
            $points['team1'] = 16;
        } else if ($difference > 1800) {
            $points['team1'] = 15;
        } else if ($difference > 1350) {
            $points['team1'] = 14;
        } else if ($difference > 900) {
            $points['team1'] = 13;
        } else if ($difference > 450) {
            $points['team1'] = 12;
        } else if ($difference > 225) {
            $points['team1'] = 11;
        } else if ($difference > -226) {
            $points['team1'] = 10;
        } else if ($difference > -451) {
            $points['team1'] = 9;
        } else if ($difference > -901) {
            $points['team1'] = 8;
        } else if ($difference > -1351) {
            $points['team1'] = 7;
        } else if ($difference > -1801) {
            $points['team1'] = 6;
        } else if ($difference > -2251) {
            $points['team1'] = 5;
        } else if ($difference > -3151) {
            $points['team1'] = 4;
        } else {
            $points['team1'] = 3;
        }

        $points['team2'] = 20 - $points['team1'];

        if ($resultsData['team1Scenario']) {
            $points['team1'] += 3;
            $points['team2'] -= 3;
        }

        if ($resultsData['team2Scenario']) {
            $points['team1'] -= 3;
            $points['team2'] += 3;
        }

        return $points;
    }
}