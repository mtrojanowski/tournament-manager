<?php
namespace TournamentBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ORM\Mapping\Id;

/**
 * @MongoDB\Document
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
     * @MongoDB\Field(type="string")
     */
    private $club;

    /**
     * @MongoDB\EmbedMany(targetDocument="Player")
     */
    private $members;
    public function __construct()
    {
        $this->members = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * Get id
     *
     * @return id $id
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
     * @param TournamentBundle\Document\Player $member
     */
    public function addMember(\TournamentBundle\Document\Player $member)
    {
        $this->members[] = $member;
    }

    /**
     * Remove member
     *
     * @param TournamentBundle\Document\Player $member
     */
    public function removeMember(\TournamentBundle\Document\Player $member)
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
}
