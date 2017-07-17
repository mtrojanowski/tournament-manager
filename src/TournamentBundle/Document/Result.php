<?php
namespace TournamentBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Result
 * @package TournamentBundle\Document
 * @MongoDB\EmbeddedDocument
 */
class Result
{
    /**
     * @MongoDB\Field(type="integer")
     */
    private $roundNo;

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
     * @MongoDB\Field(type="string")
     */
    private $teamName;

    /**
     * @MongoDB\Field(type="string")
     */
    private $teamCountry;

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
     * Set roundNo
     *
     * @param integer $roundNo
     * @return $this
     */
    public function setRoundNo($roundNo)
    {
        $this->roundNo = $roundNo;
        return $this;
    }

    /**
     * Get roundNo
     *
     * @return integer $roundNo
     */
    public function getRoundNo()
    {
        return $this->roundNo;
    }
}
