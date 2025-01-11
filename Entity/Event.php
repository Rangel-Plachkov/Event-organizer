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

    public function __construct(
        $id,
        $title,
        $eventDate,
        $type = 'Other',
        $visibility = 'public',
        $hasOrganization = false,
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->eventDate = $eventDate;
        $this->type = $type;
        $this->visibility = $visibility;
        $this->hasOrganization = $hasOrganization;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getEventDate()
    {
        return $this->eventDate;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getVisibility()
    {
        return $this->visibility;
    }

    public function getHasOrganization()
    {
        return $this->hasOrganization;
    }
}
