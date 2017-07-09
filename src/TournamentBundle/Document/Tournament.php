<?php
namespace TournamentBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Tournament
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Field(type="date")
     */
    protected $date;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $name;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $externalId;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $type;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $status;

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
     * Set date
     *
     * @param \DateTime $date
     * @return $this
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime $date
     */
    public function getDate():\DateTime
    {
        return $this->date;
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
     * Set externalId
     *
     * @param string $externalId
     * @return $this
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
        return $this;
    }

    /**
     * Get externalId
     *
     * @return string $externalId
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return string $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return string $status
     */
    public function getStatus()
    {
        return $this->status;
    }
}
