<?php
namespace TournamentBundle\Service;


use Doctrine\Common\Collections\Collection;
use TournamentBundle\Document\Table;
use TournamentBundle\Document\Team;
use TournamentBundle\Document\TeamResult;

class PairingService
{
    const POLAND = 'Poland';

    /**
     * @param array $teams Array of TournamentBundle\Document\Team objects
     * @param bool $isFirstRound
     *
     * @return array Array of TournamentBundle\Document\Table objects:
     *               - sorted by first MatchPoints then BattlePoints
     *               - if in first round paired so that teams from the same club / country (other than Poland) don't play together
     *               - in other rounds so that teams haven't played with each other previously
     */
    public function createPairing(array $teams, bool $isFirstRound):array
    {
        $tables = [];
        $tableNo = 1;
        $teamsCount = count($teams);

        foreach ($teams as $i => &$team) {
            $nextIndex = $this->getNextIndex($i, $teams, $teamsCount);
            if ($nextIndex == -1) {
                $tables[] = $this->createTable($tableNo, $team, null);
                continue;
            }

            $opponentIndex = $this->findFirstPossibleOpponent($team, $nextIndex, $teams, $nextIndex, $isFirstRound, $teamsCount);
            $opponent = $teams[$opponentIndex];

            $tables[] = $this->createTable($tableNo, $team, $opponent);
            unset($teams[$opponentIndex]);
            $tableNo++;
        }
        unset($team);

        return $tables;
    }

    /**
     * @param Collection $tables Array of Table objects with all pairings
     * @param array $switchData Data needed to switch teams
     *
     * @return Collection Array of Table objects with switched teams;
     */
    public function switchTeams(Collection $tables, array $switchData):Collection
    {
        $sourceTableIndex = $this->findIndexOfTable($tables, $switchData['sourceTableNo']);
        $targetTableIndex = $this->findIndexOfTable($tables, $switchData['targetTableNo']);

        if ($switchData['sourceTeamNo'] == 1) {
            $sourceTeam = $tables[$sourceTableIndex]->getTeam1();
        } else {
            $sourceTeam = $tables[$sourceTableIndex]->getTeam2();
        }


        if ($switchData['targetTeamNo'] == 1) {
            $targetTeam = $tables[$targetTableIndex]->getTeam1();
        } else {
            $targetTeam = $tables[$targetTableIndex]->getTeam2();
        }

        if ($switchData['sourceTeamNo'] == 1) {
            $tables[$sourceTableIndex]->setTeam1($targetTeam);
        } else {
            $tables[$sourceTableIndex]->setTeam2($targetTeam);
        }

        if ($switchData['targetTeamNo'] == 1) {
            $tables[$targetTableIndex]->setTeam1($sourceTeam);
        } else {
            $tables[$targetTableIndex]->setTeam2($sourceTeam);
        }

        return $tables;
    }

    private function findIndexOfTable(Collection $tables, int $tableNo)
    {
        foreach ($tables as $i => $table) {
            /** @var Table $table */
            if ($table->getTableNo() === $tableNo) {
                return $i;
            }
        }
    }

    private function getNextIndex(int $i, array $teams, int $max):int
    {
        $nextIndex = $i +1;
        while (!isset($teams[$nextIndex])) {
            $nextIndex++;

            if ($nextIndex > $max) {
                return -1;
            }
        }

        return $nextIndex;
    }

    private function createTable(int $no, Team $team1, ?Team $team2):Table
    {
        $table = new Table();
        $table->setTableNo($no);
        $table->setTeam1($this->mapTeam($team1));
        if ($team2 !== null) {
            $table->setTeam2($this->mapTeam($team2));
        }

        return $table;
    }

    private function mapTeam(Team $team):TeamResult
    {
        $teamResult = new TeamResult();
        $teamResult->setTeamId($team->getId());
        $teamResult->setTeamName($team->getName());
        $teamResult->setTeamClub($team->getClub());
        $teamResult->setTeamCountry($team->getCountry());

        return $teamResult;
    }

    private function teamsCanPlayTogether(Team $team1, Team $team2, bool $isFirstRound):bool {
        if ($isFirstRound) {
            if ($team1->getCountry() !== PairingService::POLAND) {
                return $team1->getCountry() !== $team2->getCountry();
            }

            return $team1->getClub() !== $team2->getClub();
        }

        return true;
    }

    private function findFirstPossibleOpponent(Team $team, int $originalOpponent, array $teams, int $offset, bool $isFirstRound, int $max):int
    {
        $nextOpponentIndex = $offset;
        $possibleOpponent = $teams[$nextOpponentIndex];

        while (!$this->teamsCanPlayTogether($team, $possibleOpponent, $isFirstRound)) {
            $nextOpponentIndex = $this->getNextIndex($nextOpponentIndex, $teams, $max);

            if ($nextOpponentIndex === -1) {
                return $originalOpponent;
            }

            $possibleOpponent = $teams[$nextOpponentIndex];
        }

        return $nextOpponentIndex;
    }
}