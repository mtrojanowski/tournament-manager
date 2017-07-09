<?php
namespace TournamentBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\EmbeddedDocument
 */
class Player
{
    /**
     * @MongoDB\Field(type="string")
     */
    private $id;

    /**
     * @MongoDB\Field(type="string")
     */
    private $name;

    /**
     * @MongoDB\Field(type="integer")
     */
    private $wfbPolId;

    /**
     * @MongoDB\Field(type="string")
     */
    private $roster;

    /**
     * Set id
     *
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * Set wfbPolId
     *
     * @param integer $wfbPolId
     * @return $this
     */
    public function setWfbPolId($wfbPolId)
    {
        $this->wfbPolId = $wfbPolId;
        return $this;
    }

    /**
     * Get wfbPolId
     *
     * @return integer $wfbPolId
     */
    public function getWfbPolId()
    {
        return $this->wfbPolId;
    }

    /**
     * Set roster
     *
     * @param string $roster
     * @return $this
     */
    public function setRoster($roster)
    {
        $this->roster = $roster;
        return $this;
    }

    /**
     * Get roster
     *
     * @return string $roster
     */
    public function getRoster()
    {
        return $this->roster;
    }
}
