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
    private $opponentId;

    /**
     * @MongoDB\Field(type="string")
     */
    private $opponentName;

    /**
     * @MongoDB\Field(type="string")
     */
    private $opponentCountry;

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

    /**
     * Set opponentId
     *
     * @param string $opponentId
     * @return $this
     */
    public function setOpponentId($opponentId)
    {
        $this->opponentId = $opponentId;
        return $this;
    }

    /**
     * Get opponentId
     *
     * @return string $opponentId
     */
    public function getOpponentId()
    {
        return $this->opponentId;
    }

    /**
     * Set opponentName
     *
     * @param string $opponentName
     * @return $this
     */
    public function setOpponentName($opponentName)
    {
        $this->opponentName = $opponentName;
        return $this;
    }

    /**
     * Get opponentName
     *
     * @return string $opponentName
     */
    public function getOpponentName()
    {
        return $this->opponentName;
    }

    /**
     * Set opponentCountry
     *
     * @param string $opponentCountry
     * @return $this
     */
    public function setOpponentCountry($opponentCountry)
    {
        $this->opponentCountry = $opponentCountry;
        return $this;
    }

    /**
     * Get opponentCountry
     *
     * @return string $opponentCountry
     */
    public function getOpponentCountry()
    {
        return $this->opponentCountry;
    }
}
