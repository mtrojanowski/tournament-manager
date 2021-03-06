<?php
namespace TournamentBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Table
 * @package TournamentBundle\Document
 * @MongoDB\EmbeddedDocument
 */
class Table
{

    /**
     * @MongoDB\Field(type="integer")
     */
    private $tableNo;

    /**
     * @var TeamResult $team1
     * @MongoDB\EmbedOne(targetDocument="TeamResult")
     */
    private $team1;

    /**
     * @var TeamResult $team2
     * @MongoDB\EmbedOne(targetDocument="TeamResult")
     */
    private $team2;

    /**
     * Set tableNo
     *
     * @param integer $tableNo
     * @return $this
     */
    public function setTableNo($tableNo)
    {
        $this->tableNo = $tableNo;
        return $this;
    }

    /**
     * Get tableNo
     *
     * @return integer $tableNo
     */
    public function getTableNo()
    {
        return $this->tableNo;
    }

    /**
     * Set team1
     *
     * @param TeamResult $team1
     * @return $this
     */
    public function setTeam1(TeamResult $team1)
    {
        $this->team1 = $team1;
        return $this;
    }

    /**
     * Get team1
     *
     * @return TeamResult $team1
     */
    public function getTeam1()
    {
        return $this->team1;
    }

    /**
     * Set team2
     *
     * @param TeamResult $team2
     * @return $this
     */
    public function setTeam2(TeamResult $team2)
    {
        $this->team2 = $team2;
        return $this;
    }

    /**
     * Get team2
     *
     * @return TeamResult $team2
     */
    public function getTeam2()
    {
        return $this->team2;
    }

    public function __toString()
    {
        return sprintf('Table { no: %s, team1: %s, team2: %s }', $this->tableNo, $this->team1, $this->team2);
    }

    public function getTeam2Id():?int
    {
        return $this->team2 !== null ? $this->team2->getTeamId() : null;
    }

    public function getResultsData():array
    {
        return [
            'team1MatchPoints' => $this->team1->getMatchPoints(),
            'team1Penalty' => $this->team1->getPenalty(),
            'team1Scenario' => $this->team1->getScenario(),
            'team2MatchPoints' => empty($this->team2) ? null : $this->team2->getMatchPoints() ,
            'team2Penalty' => empty($this->team2) ? null : $this->team2->getPenalty(),
            'team2Scenario' => empty($this->team2) ? null : $this->team2->getScenario(),
            'tableNo' => $this->tableNo
        ];
    }

    public function resultsSet():bool
    {
        return $this->team1->getMatchPoints() !== null;
    }
}
