<?php
namespace TournamentBundle\Service;

use PHPUnit\Framework\TestCase;
use TournamentBundle\Document\Table;
use TournamentBundle\Document\Team;
use TournamentBundle\Document\TeamResult;

class ResultsServiceTest extends TestCase
{
    /**
     * @dataProvider getResultData
     */
    public function testSetResultSetProperResult(Table $table, array $resultsData, Table $expectedResult)
    {
        $service = new ResultsService();

        $service->setTableResult($table, $resultsData);

        $this->assertEquals($expectedResult, $table);
    }

    public function getResultData()
    {
        return [
            [
                'table' => $this->createInitialTable(),
                'resultsData' => ['team1MatchPoints' => 51, 'team1Penalty' => 0, 'team2MatchPoints' => 49, 'team2Penalty' => 0],
                'expectedResult' => $this->createTable(51, 51, 0, 49, 49, 0),
            ],
            [
                'table' => $this->createInitialTable(),
                'resultsData' => ['team1MatchPoints' => 25, 'team1Penalty' => 0, 'team2MatchPoints' => 75, 'team2Penalty' => 0],
                'expectedResult' => $this->createTable(25, 40, 0, 75, 60, 0),
            ],
            [
                'table' => $this->createInitialTable(),
                'resultsData' => ['team1MatchPoints' => 51, 'team1Penalty' => 2, 'team2MatchPoints' => 49, 'team2Penalty' => 4],
                'expectedResult' => $this->createTable(51, 51, 2, 49, 49, 4),
            ],
        ];
    }

    private function createInitialTable():Table
    {
        $table = new Table();
        $table
            ->setTableNo(1)
            ->setTeam1($this->createTeamResult('1', null, null, null))
            ->setTeam2($this->createTeamResult('2', null, null, null));

        return $table;
    }

    private function createTable(int $mp1, int $bp1, int $penalty1, int $mp2, int $bp2, int $penalty2):Table
    {
        $table = new Table();
        $table
            ->setTableNo(1)
            ->setTeam1($this->createTeamResult('1', $mp1, $bp1, $penalty1))
            ->setTeam2($this->createTeamResult('2', $mp2, $bp2, $penalty2));
        return $table;
    }

    private function createTeamResult(string $teamId, ?int $mp, ?int $bp, ?int $penalty):TeamResult
    {
        $teamResult = new TeamResult();
        $teamResult
            ->setTeamId($teamId)
            ->setMatchPoints($mp)
            ->setBattlePoints($bp)
            ->setPenalty($penalty);
        return $teamResult;
    }
}
