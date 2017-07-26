<?php
namespace TournamentBundle\Document;

use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Round
 * @package TournamentBundle\Document
 * @MongoDB\Document
 */
class Round
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
     * @MongoDB\Field(type="integer")
     */
    private $roundNo;

    /**
     * @MongoDB\Field(type="boolean")
     */
    private $verified;

    /**
     * @MongoDB\Field(type="boolean")
     */
    private $started;

    /**
     * @MongoDB\Field(type="timestamp")
     */
    private $startedAt;

    /**
     * @MongoDB\Field(type="timestamp")
     */
    private $finishedAt;

    /**
     * @MongoDB\EmbedMany(targetDocument="Table")
     */
    private $tables;

    public function __construct()
    {
        $this->tables = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add table
     *
     * @param Table $table
     */
    public function addTable(Table $table)
    {
        $this->tables[] = $table;
    }

    /**
     * Remove table
     *
     * @param Table $table
     */
    public function removeTable(Table $table)
    {
        $this->tables->removeElement($table);
    }

    /**
     * Get tables
     *
     * @return \Doctrine\Common\Collections\Collection $tables
     */
    public function getTables()
    {
        return $this->tables;
    }

    public function setTables(Collection $tables)
    {
        $this->tables = $tables;
    }

    public function getTable(int $tableNo):?Table
    {
        foreach ($this->tables as $table) {
            /** @var Table $table */
            if ($table->getTableNo() === $tableNo) {
                return $table;
            }
        }

        return null;
    }

    public function setTable(int $tableNo, Table $newTable):void
    {
        foreach ($this->tables as $i => $table) {
            /** @var Table $table */
            if ($table->getTableNo() === $tableNo) {
                $this->tables[$i] = $newTable;
            }
        }
    }

    /**
     * Set verified
     *
     * @param boolean $verified
     * @return $this
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;
        return $this;
    }

    /**
     * Get verified
     *
     * @return boolean $verified
     */
    public function getVerified()
    {
        return $this->verified;
    }

    /**
     * Set started
     *
     * @param boolean $started
     * @return $this
     */
    public function setStarted($started)
    {
        $this->started = $started;
        return $this;
    }

    /**
     * Get started
     *
     * @return boolean $started
     */
    public function getStarted()
    {
        return $this->started;
    }

    /**
     * Set startedAt
     *
     * @param int $startedAt
     * @return $this
     */
    public function setStartedAt($startedAt)
    {
        $this->startedAt = $startedAt;
        return $this;
    }

    /**
     * Get startedAt
     *
     * @return int $startedAt
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * Set finishedAt
     *
     * @param int $finishedAt
     * @return $this
     */
    public function setFinishedAt($finishedAt)
    {
        $this->finishedAt = $finishedAt;
        return $this;
    }

    /**
     * Get finishedAt
     *
     * @return int $finishedAt
     */
    public function getFinishedAt()
    {
        return $this->finishedAt;
    }
}
