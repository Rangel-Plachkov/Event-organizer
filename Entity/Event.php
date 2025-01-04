<?php

namespace Entity;

class Event
{
    private $id;
    private $title;
    private $eventDate;
    private $type;
    private $visibility;
    private $hasOrganization;
    private $organizerId;
    private $isAnonymous;
    private $excludedUserId;

    /**
     * @param $id
     * @param $title
     * @param $eventDate
     * @param $type
     * @param $visibility
     * @param $hasOrganization
     * @param $organizerId
     * @param $isAnonymous
     * @param $excludedUserId
     */
    public function __construct($id, $title, $eventDate, $type, $visibility, $hasOrganization, $organizerId, $isAnonymous, $excludedUserId)
    {
        $this->id = $id;
        $this->title = $title;
        $this->eventDate = $eventDate;
        $this->type = $type;
        $this->visibility = $visibility;
        $this->hasOrganization = $hasOrganization;
        $this->organizerId = $organizerId;
        $this->isAnonymous = $isAnonymous;
        $this->excludedUserId = $excludedUserId;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getEventDate()
    {
        return $this->eventDate;
    }

    /**
     * @param mixed $eventDate
     */
    public function setEventDate($eventDate): void
    {
        $this->eventDate = $eventDate;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * @param mixed $visibility
     */
    public function setVisibility($visibility): void
    {
        $this->visibility = $visibility;
    }

    /**
     * @return mixed
     */
    public function getHasOrganization()
    {
        return $this->hasOrganization;
    }

    /**
     * @param mixed $hasOrganization
     */
    public function setHasOrganization($hasOrganization): void
    {
        $this->hasOrganization = $hasOrganization;
    }

    /**
     * @return mixed
     */
    public function getOrganizerId()
    {
        return $this->organizerId;
    }

    /**
     * @param mixed $organizerId
     */
    public function setOrganizerId($organizerId): void
    {
        $this->organizerId = $organizerId;
    }

    /**
     * @return mixed
     */
    public function getIsAnonymous()
    {
        return $this->isAnonymous;
    }

    /**
     * @param mixed $isAnonymous
     */
    public function setIsAnonymous($isAnonymous): void
    {
        $this->isAnonymous = $isAnonymous;
    }

    /**
     * @return mixed
     */
    public function getExcludedUserId()
    {
        return $this->excludedUserId;
    }

    /**
     * @param mixed $excludedUserId
     */
    public function setExcludedUserId($excludedUserId): void
    {
        $this->excludedUserId = $excludedUserId;
    }
}
