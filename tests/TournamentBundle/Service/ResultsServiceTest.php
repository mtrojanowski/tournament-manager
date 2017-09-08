<?php
namespace TournamentBundle\Service;

use PHPUnit\Framework\TestCase;
use TournamentBundle\Document\Table;
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
                'expectedResult' => $this->createTable(51, 51, 0, null, 49, 49, 0, null),
            ],
            [
                'table' => $this->createInitialTable(),
                'resultsData' => ['team1MatchPoints' => 25, 'team1Penalty' => 0, 'team2MatchPoints' => 75, 'team2Penalty' => 0],
                'expectedResult' => $this->createTable(25, 35, 0, null, 75, 65, 0, null),
            ],
            [
                'table' => $this->createInitialTable(),
                'resultsData' => ['team1MatchPoints' => 51, 'team1Penalty' => 2, 'team2MatchPoints' => 49, 'team2Penalty' => 4],
                'expectedResult' => $this->createTable(51, 51, 2, null, 49, 49, 4, null),
            ],
            [
                'table' => $this->createInitialTableWithBye(),
                'resultsData' => ['team1MatchPoints' => 55, 'team1Penalty' => 0, 'team2MatchPoints' => null, 'team2Penalty' => null],
                'expectedResult' => $this->createTable(55, 55, 0, null, null, null, null, null),
            ],

        ];
    }

    /**
     * @dataProvider getSingleResultData
     */
    public function testSetSingleResultSetProperResult(Table $table, array $resultsData, Table $expectedResult)
    {
        $service = new ResultsService();

        $service->setTableSingleResult($table, $resultsData);

        $this->assertEquals($expectedResult, $table);
    }

    public function getSingleResultData()
    {
        return [
            [
                'table' => $this->createInitialTable(),
                'resultsData' => ['team1MatchPoints' => 1200, 'team1Scenario' => 0, 'team1Penalty' => 0, 'team2MatchPoints' => 0, 'team2Scenario' => 0, 'team2Penalty' => 0],
                'expectedResult' => $this->createTable(1200, 13, 0,false, 0, 7, 0, false),
            ],
            [
                'table' => $this->createInitialTable(),
                'resultsData' => ['team1MatchPoints' => 230, 'team1Scenario' => 0, 'team1Penalty' => 0, 'team2MatchPoints' => 3500, 'team2Scenario' => 0, 'team2Penalty' => 0],
                'expectedResult' => $this->createTable(230, 3, 0, false, 3500, 17, 0, false),
            ],
            [
                'table' => $this->createInitialTable(),
                'resultsData' => ['team1MatchPoints' => 300, 'team1Scenario' => 1, 'team1Penalty' => 0, 'team2MatchPoints' => 280, 'team2Scenario' => 0, 'team2Penalty' => 0],
                'expectedResult' => $this->createTable(300, 13, 0,true, 280, 7, 0, false),
            ],
            [
                'table' => $this->createInitialTable(),
                'resultsData' => ['team1MatchPoints' => 330, 'team1Scenario' => 0, 'team1Penalty' => 0, 'team2MatchPoints' => 0, 'team2Scenario' => 1, 'team2Penalty' => 0],
                'expectedResult' => $this->createTable(330, 8, 0, false, 0, 12, 0, true),
            ],

        ];
    }


    private function createInitialTable():Table
    {
        $table = new Table();
        $table
            ->setTableNo(1)
            ->setTeam1($this->createTeamResult('1', null, null, null, null))
            ->setTeam2($this->createTeamResult('2', null, null, null, null));

        return $table;
    }
    private function createInitialTableWithBye():Table
    {
        $table = new Table();
        $table
            ->setTableNo(1)
            ->setTeam1($this->createTeamResult('1', null, null, null, null));

        return $table;
    }

    private function createTable(int $mp1, int $bp1, int $penalty1, ?bool $sc1, ?int $mp2, ?int $bp2, ?int $penalty2, ?bool $sc2):Table
    {
        $table = new Table();
        $table
            ->setTableNo(1)
            ->setTeam1($this->createTeamResult('1', $mp1, $bp1, $penalty1, $sc1));

        if ($mp2 !== null) {
            $table->setTeam2($this->createTeamResult('2', $mp2, $bp2, $penalty2, $sc2));
        }

        return $table;
    }

    private function createTeamResult(string $teamId, ?int $mp, ?int $bp, ?int $penalty, ?bool $scenario):TeamResult
    {
        $teamResult = new TeamResult();
        $teamResult
            ->setTeamId($teamId)
            ->setMatchPoints($mp)
            ->setBattlePoints($bp)
            ->setScenario($scenario)
            ->setPenalty($penalty);
        return $teamResult;
    }
}
