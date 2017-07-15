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
}
