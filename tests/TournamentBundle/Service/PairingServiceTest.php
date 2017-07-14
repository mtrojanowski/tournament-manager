<?php
namespace Tests\TournamentBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use TournamentBundle\Document\Table;
use TournamentBundle\Document\Team;
use TournamentBundle\Document\TeamResult;
use TournamentBundle\Service\PairingService;

class PairingServiceTest extends TestCase
{
    /**
     * Tables should have teams which:
     *  - are not from the same country unless it's Poland
     *  - are not from the same club if from Poland
     *  - this does not apply to the last table - the last table can have teams from the same country/club
     *
     */
    public function testPairingProducesValidTablesForRoundOne()
    {
        $teams = [
            $this->createTeam('1', 'ZKF Ad Astra Alfa', 'Poland', 'Ad Astra'),
            $this->createTeam('2', 'ZKF Ad Astra Beta', 'Poland', 'Ad Astra'),
            $this->createTeam('3', 'Osmiornica 1', 'Poland', 'Osmiornica'),
            $this->createTeam('4', 'Bila Alfa', 'Poland', 'Osma Bila'),

            $this->createTeam('5', 'UK Alfa', 'UK', 'Some club'),
            $this->createTeam('6', 'UK Beta', 'UK', 'other club'),
            $this->createTeam('7', 'Szybki Szpil Alfa', 'Poland', 'Szybki Szpil'),
            $this->createTeam('8', 'Italy Alfa', 'Italy', 'clubbio italiano'),

            $this->createTeam('9', 'Polska 9', 'Poland', 'klub 2'),
            $this->createTeam('10', 'Polska 10', 'Poland', 'klub 3'),

            $this->createTeam('11', 'Polska 11', 'Poland', 'klub 3'),
            $this->createTeam('12', 'Polska 12', 'Poland', 'klub 3'),
            $this->createTeam('13', 'Polska 13', 'Poland', 'klub 3'),
            $this->createTeam('14', 'UK 14', 'UK', 'club 13'),
            $this->createTeam('15', 'UK 15', 'UK', 'club 13'),
            $this->createTeam('16', 'UK 16', 'UK', 'club 13'),

            $this->createTeam('17', 'UK 17', 'UK', 'club 13'),
            $this->createTeam('18', 'UK 18', 'UK', 'club 13'),

        ];
        $service = new PairingService();
        $actualResults = $service->createPairing($teams, true);

        $expectedPairing = [
            $this->createTable(1, '1', '3'),
            $this->createTable(2, '2', '4'),
            $this->createTable(3, '5', '7'),
            $this->createTable(4, '6', '8'),
            $this->createTable(5, '9', '10'),
            $this->createTable(6, '11', '14'),
            $this->createTable(7, '12', '15'),
            $this->createTable(8, '13', '16'),
            $this->createTable(9, '17', '18'),
        ];

        $this->compareTables($expectedPairing, $actualResults);
    }

    public function testPairingForRoundOneProducesBye()
    {
        $teams = [
            $this->createTeam('1', 'ZKF Ad Astra Alfa', 'Poland', 'Ad Astra'),
            $this->createTeam('2', 'ZKF Ad Astra Beta', 'Poland', 'Ad Astra'),
            $this->createTeam('3', 'Osmiornica 1', 'Poland', 'Osmiornica'),
        ];
        $service = new PairingService();
        $actualResults = $service->createPairing($teams, true);

        $expectedPairing = [
            $this->createTable(1, '1', '3'),
            $this->createTable(2, '2', null),
        ];
        $this->compareTables($expectedPairing, $actualResults);
    }

    public function testPairingForRoundOneProducesByeCaseTwo()
    {
        $teams = [
            $this->createTeam('1', 'ZKF Ad Astra Alfa', 'Poland', 'Ad Astra'),
            $this->createTeam('2', 'ZKF Ad Astra Beta', 'Poland', 'Other Team'),
            $this->createTeam('3', 'Osmiornica 1', 'Poland', 'Osmiornica'),
        ];
        $service = new PairingService();
        $actualResults = $service->createPairing($teams, true);

        $expectedPairing = [
            $this->createTable(1, '1', '2'),
            $this->createTable(2, '3', null),
        ];

        $this->compareTables($expectedPairing, $actualResults);
    }

    public function testSwitchTablesShouldProperlySwitchTeams()
    {
        $tables = new ArrayCollection([
            $this->createTable(1, '1', '2'),
            $this->createTable(2, '3', '4'),
        ]);

        $expectedTables = new ArrayCollection([
            $this->createTable(1, '1', '3'),
            $this->createTable(2, '2', '4'),
        ]);

        $switchData = [
            'sourceTableNo' => 1,
            'sourceTeamNo' => 2,
            'targetTableNo' => 2,
            'targetTeamNo' => 1,
        ];

        $service = new PairingService();
        $actualTables = $service->switchTeams($tables, $switchData);

        $this->assertEquals($expectedTables, $actualTables);
    }

    private function compareTables(array $expectedPairing, array $actualResults):void
    {
        $countExpected = count($expectedPairing);
        $countActual = count($actualResults);
        $this->assertEquals(
            $countExpected,
            $countActual,
            sprintf('The size of the result does not match, expected: %s, actual: %s', $countExpected, $countActual)
        );

        foreach ($actualResults as $i => $actualTable) {
            /** @var Table $actualTable */
            /** @var Table $expectedTable */
            $expectedTable = $expectedPairing[$i];
            $this->assertEquals(
                $expectedTable->getTableNo(),
                $actualTable->getTableNo(),
                sprintf('Table numbers do not match at index %s, expected: %s, actual: %s', $i, $expectedTable->getTableNo(), $actualTable->getTableNo())
            );
            $this->assertEquals(
                $expectedTable->getTeam1()->getTeamId(),
                $actualTable->getTeam1()->getTeamId(),
                sprintf('Team1 at table %s do not match, expected: %s, actual: %s', $actualTable->getTableNo(), $expectedTable->getTeam1()->getTeamId(), $actualTable->getTeam1()->getTeamId())
            );
            $this->assertEquals(
                $expectedTable->getTeam2Id(),
                $actualTable->getTeam2Id(),
                sprintf('Team2 at table %s do not match, expected: %s, actual: %s', $actualTable->getTableNo(), $expectedTable->getTeam2Id(), $actualTable->getTeam2Id())
            );
        }
    }

    private function createTeam(string $id, string $name, string $country, string $club):Team
    {
        $team = new Team();
        $team->setId($id);
        $team->setName($name);
        $team->setCountry($country);
        $team->setClub($club);

        return $team;
    }

    private function createTeamResult(string $teamId):TeamResult
    {
        $teamResult = new TeamResult();
        $teamResult->setTeamId($teamId);

        return $teamResult;
    }

    private function createTable(int $tableNo, string $team1Id, ?string $team2Id):Table
    {
        $table = new Table();
        $table->setTableNo($tableNo);
        $table->setTeam1($this->createTeamResult($team1Id));

        if ($team2Id !== null) {
            $table->setTeam2($this->createTeamResult($team2Id));
        }

        return $table;
    }
}
