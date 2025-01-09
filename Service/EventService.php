<?php

namespace Service;

use Repository\EventRepository;
use Entity\Event;
use http\SessionHandler;

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

    public function getPublicOrInvitedEvents(): array
    {
        $session = SessionHandler::getInstance();
        $$userId = $session->getSessionValue('userId');

        if (empty($userId)) {
            throw new \InvalidArgumentException('User ID is required.');
        }

        return $this->eventRepository->getPublicOrInvitedEvents($userId);
    }
}
