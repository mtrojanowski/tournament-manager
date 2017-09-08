<?php
namespace TournamentBundle\Document;

/**
 * Class TeamResult
 * @package TournamentBundle\Document
 * @MongoDB\EmbeddedDocument
 */
class TeamResult
{
    /**
     * @MongoDB\Field(type="string")
     */
    private $teamId;

    /**
     * @MongoDB\Field(type="string")
     */
    private $teamName;

    /**
     * @MongoDB\Field(type="string")
     */
    private $teamCountry;

    /**
     * @MongoDB\Field(type="string")
     */
    private $teamClub;

    /**
     * @MongoDB\Field(type="integer")
     */
    private $matchPoints;

    /**
     * @MongoDB\Field(type="integer")
     */
    private $battlePoints;

    /**
     * @MongoDB\Field(type="boolean")
     */
    private $scenario;

    /**
     * @MongoDB\Field(type="integer")
     */
    private $penalty;

    /**
     * Set teamId
     *
     * @param string $teamId
     * @return $this
     */
    public function setTeamId($teamId)
    {
        $this->teamId = $teamId;
        return $this;
    }

    /**
     * Get teamId
     *
     * @return string $teamId
     */
    public function getTeamId()
    {
        return $this->teamId;
    }

    /**
     * Set teamName
     *
     * @param string $teamName
     * @return $this
     */
    public function setTeamName($teamName)
    {
        $this->teamName = $teamName;
        return $this;
    }

    /**
     * Get teamName
     *
     * @return string $teamName
     */
    public function getTeamName()
    {
        return $this->teamName;
    }

    /**
     * Set matchPoints
     *
     * @param integer $matchPoints
     * @return $this
     */
    public function setMatchPoints($matchPoints)
    {
        $this->matchPoints = $matchPoints;
        return $this;
    }

    /**
     * Get matchPoints
     *
     * @return integer $matchPoints
     */
    public function getMatchPoints()
    {
        return $this->matchPoints;
    }

    /**
     * Set battlePoints
     *
     * @param integer $battlePoints
     * @return $this
     */
    public function setBattlePoints($battlePoints)
    {
        $this->battlePoints = $battlePoints;
        return $this;
    }

    /**
     * Get battlePoints
     *
     * @return integer $battlePoints
     */
    public function getBattlePoints()
    {
        return $this->battlePoints;
    }

    /**
     * Set penalty
     *
     * @param integer $penalty
     * @return $this
     */
    public function setPenalty($penalty)
    {
        $this->penalty = $penalty;
        return $this;
    }

    /**
     * Get penalty
     *
     * @return integer $penalty
     */
    public function getPenalty()
    {
        return $this->penalty;
    }

    /**
     * Set teamCountry
     *
     * @param string $teamCountry
     * @return $this
     */
    public function setTeamCountry($teamCountry)
    {
        $this->teamCountry = $teamCountry;
        return $this;
    }

    /**
     * Get teamCountry
     *
     * @return string $teamCountry
     */
    public function getTeamCountry()
    {
        return $this->teamCountry;
    }

    /**
     * Set teamClub
     *
     * @param string $teamClub
     * @return $this
     */
    public function setTeamClub($teamClub)
    {
        $this->teamClub = $teamClub;
        return $this;
    }

    /**
     * Get teamClub
     *
     * @return string $teamClub
     */
    public function getTeamClub()
    {
        return $this->teamClub;
    }

    /**
     * Set scenario
     *
     * @param boolean $scenario
     * @return $this
     */
    public function setScenario($scenario)
    {
        $this->scenario = $scenario;
        return $this;
    }

    /**
     * Get scenario
     *
     * @return boolean $scenario
     */
    public function getScenario()
    {
        return $this->scenario;
    }
}
