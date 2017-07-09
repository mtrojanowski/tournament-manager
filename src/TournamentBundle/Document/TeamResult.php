<?php
namespace TournamentBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

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
     * @MongoDB\Field(type="integer")
     */
    private $matchPoints;

    /**
     * @MongoDB\Field(type="integer")
     */
    private $battlePoints;

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
}
