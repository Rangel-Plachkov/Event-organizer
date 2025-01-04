<?php

namespace Service;

use Repository\EventRepository;
use Entity\Event;

class EventService
{
    private EventRepository $eventRepository;

    public function __construct()
    {
        $this->eventRepository = new EventRepository();
    }

    public function createEvent(Event $event)
    {
        //Object validation
        if (empty($event->getTitle()) || empty($event->getEventDate())) {
            throw new \InvalidArgumentException('Title and event date are required.');
        }

        return $this->eventRepository->createEvent($event);
    }
}
