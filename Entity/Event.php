<?php

namespace Entity;

class Event
{
    private int $id;
    private string $title;
    private string $eventDate;
    private string $type;
    private string $visibility;
    private bool $hasOrganization;

    public function __construct(
        string $title,
        string $eventDate,
        string $type = 'Other',
        string $visibility = 'public',
        bool $hasOrganization = false,
        int $id = 0
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->eventDate = $eventDate;
        $this->type = $type;
        $this->visibility = $visibility;
        $this->hasOrganization = $hasOrganization;
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getEventDate(): string
    {
        return $this->eventDate;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getVisibility(): string
    {
        return $this->visibility;
    }

    public function getHasOrganization(): bool
    {
        return $this->hasOrganization;
    }
}
