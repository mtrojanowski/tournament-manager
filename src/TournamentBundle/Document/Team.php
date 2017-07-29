<?php
namespace TournamentBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ORM\Mapping\Id;
use TournamentBundle\Controller\TournamentController;
use TournamentBundle\Service\PairingService;

/**
 * @MongoDB\Document(repositoryClass="TournamentBundle\Repository\TeamsRepository")
 */
class Team
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\Field(type="string")
     */
    private $tournamentId;

    /**
     * @MongoDB\Field(type="string")
     */
    private $name;

    /**
     * @MongoDB\Field(type="string")
     */
    private $country;

    /**
     * @MongoDB\Field(type="boolean")
     */
    private $confirmedDay1;

    /**
     * @MongoDB\Field(type="boolean")
     */
    private $confirmedDay2;

    /**
     * @MongoDB\Field(type="integer")
     */
    private $penaltyPoints;

    /**
     * @MongoDB\Field(type="integer")
     */
    private $battlePoints;

    /**
     * @MongoDB\Field(type="integer")
     */
    private $matchPoints;

    /**
     * @MongoDB\Field(type="integer")
     */
    private $finalBattlePoints;

    /**
     * @MongoDB\Field(type="string")
     */
    private $club;

    /**
     * @MongoDB\EmbedMany(targetDocument="Player")
     */
    private $members;

    /**
     * @MongoDB\EmbedMany(targetDocument="Result")
     */
    private $results;

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->results = new ArrayCollection();
    }

    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * Get id
     *
     * @return string $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Get country
     *
     * @return string $country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set confirmedDay1
     *
     * @param boolean $confirmedDay1
     * @return $this
     */
    public function setConfirmedDay1($confirmedDay1)
    {
        $this->confirmedDay1 = $confirmedDay1;
        return $this;
    }

    /**
     * Get confirmedDay1
     *
     * @return boolean $confirmedDay1
     */
    public function getConfirmedDay1()
    {
        return $this->confirmedDay1;
    }

    /**
     * Set confirmedDay2
     *
     * @param boolean $confirmedDay2
     * @return $this
     */
    public function setConfirmedDay2($confirmedDay2)
    {
        $this->confirmedDay2 = $confirmedDay2;
        return $this;
    }

    /**
     * Get confirmedDay2
     *
     * @return boolean $confirmedDay2
     */
    public function getConfirmedDay2()
    {
        return $this->confirmedDay2;
    }

    /**
     * Set penaltyPoints
     *
     * @param integer $penaltyPoints
     * @return $this
     */
    public function setPenaltyPoints($penaltyPoints)
    {
        $this->penaltyPoints = $penaltyPoints;
        return $this;
    }

    /**
     * Get penaltyPoints
     *
     * @return integer $penaltyPoints
     */
    public function getPenaltyPoints()
    {
        return $this->penaltyPoints;
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
     * Set club
     *
     * @param string $club
     * @return $this
     */
    public function setClub($club)
    {
        $this->club = $club;
        return $this;
    }

    /**
     * Get club
     *
     * @return string $club
     */
    public function getClub()
    {
        return $this->club;
    }

    /**
     * Add member
     *
     * @param Player $member
     */
    public function addMember(Player $member)
    {
        $this->members[] = $member;
    }

    /**
     * Remove member
     *
     * @param Player $member
     */
    public function removeMember(Player $member)
    {
        $this->members->removeElement($member);
    }

    /**
     * Get members
     *
     * @return \Doctrine\Common\Collections\Collection $members
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * Set tournamentId
     *
     * @param string $tournamentId
     * @return $this
     */
    public function setTournamentId($tournamentId)
    {
        $this->tournamentId = $tournamentId;
        return $this;
    }

    /**
     * Get tournamentId
     *
     * @return string $tournamentId
     */
    public function getTournamentId()
    {
        return $this->tournamentId;
    }

    /**
     * Add result
     *
     * @param Result $result
     */
    public function addResult(Result $result)
    {
        $this->results[] = $result;
    }

    public function setResultForRound(int $roundNo, Result $newResult)
    {
        $resultSet = false;

        foreach ($this->results as $i => $result) {
            /** @var Result $result */
            if ($result->getRoundNo() === $roundNo) {
                $resultSet = true;
                $this->results[$i] = $newResult;
                break;
            }
        }

        if (!$resultSet) {
            $this->results[] = $newResult;
        }

        $this->recalculateResults();
    }

    public function getResultForRound(int $roundNo):?Result
    {
        foreach ($this->results as $result) {
            /** @var Result $result */
            if ($result->getRoundNo() === $roundNo) {
                return $result;
            }
        }

        return null;
    }

    /**
     * Remove result
     *
     * @param Result $result
     */
    public function removeResult(Result $result)
    {
        $this->results->removeElement($result);
    }

    /**
     * Get results
     *
     * @return \Doctrine\Common\Collections\Collection $results
     */
    public function getResults()
    {
        return $this->results;
    }

    public function canPlayTogetherWith(Team $team2, bool $isFirstRound):bool {
        if ($isFirstRound) {
            if ($this->getCountry() !== PairingService::POLAND) {
                return $this->getCountry() !== $team2->getCountry();
            }

            return $this->getClub() !== $team2->getClub();
        }

        foreach ($this->getResults() as $team1Result) {
            /** @var Result $team1Result */
            if ($team1Result->getOpponentId() === $team2->getId()) {
                return false;
            }
        }

        return true;
    }

    public function hadByeBefore():bool {
        foreach ($this->getResults() as $team1Result) {
            /** @var Result $team1Result */
            if ($team1Result->getOpponentId() === TournamentController::BYE) {
                return true;
            }
        }

        return false;
    }

    public function getOpponents()
    {
        $opponents = [];
        foreach ($this->results as $result) {
            /** @var Result $result */
            $opponents[] = $result->getOpponentName();
        }

        return $opponents;
    }

    /**
     * Set finalMatchPoints
     *
     * @param integer $finalBattlePoints
     * @return $this
     */
    public function setFinalBattlePoints($finalBattlePoints)
    {
        $this->finalBattlePoints = $finalBattlePoints;
        return $this;
    }

    /**
     * Get finalBattlePoints
     *
     * @return integer $finalBattlePoints
     */
    public function getFinalBattlePoints()
    {
        return $this->finalBattlePoints;
    }

    public function recalculateResultsForRound(int $round)
    {
        $this->battlePoints = 0;
        $this->matchPoints = 0;
        $this->penaltyPoints = 0;
        $this->finalBattlePoints = 0;

        foreach ($this->results as $result) {
            /** @var Result $result */
            if ($result->getRoundNo() > $round) {
                continue;
            }

            $this->battlePoints += $result->getBattlePoints();
            $this->matchPoints += $result->getMatchPoints();
            $this->penaltyPoints += $result->getPenalty();
            $this->finalBattlePoints += $result->getBattlePoints() - $result->getPenalty();
        }
    }

    private function recalculateResults()
    {
        $this->battlePoints = 0;
        $this->matchPoints = 0;
        $this->penaltyPoints = 0;
        $this->finalBattlePoints = 0;

        foreach ($this->results as $result) {
            /** @var Result $result */
            $this->battlePoints += $result->getBattlePoints();
            $this->matchPoints += $result->getMatchPoints();
            $this->penaltyPoints += $result->getPenalty();
            $this->finalBattlePoints += $result->getBattlePoints() - $result->getPenalty();
        }
    }
}
