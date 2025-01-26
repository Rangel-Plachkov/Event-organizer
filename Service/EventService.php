<?php

namespace Service;

use Repository\EventOrganizationRepository;
use Repository\EventRepository;
use Entity\Event;
use Repository\ParticipantsRepository;

class EventService
{
    private EventRepository $eventRepository;
    private EventOrganizationRepository $eventOrganizationRepository;
    private ParticipantsRepository $participantsRepository;

    public function __construct()
    {
        $this->eventRepository = new EventRepository();
        $this->eventOrganizationRepository = new EventOrganizationRepository();
        $this->participantsRepository = new ParticipantsRepository();
    }

    public function createEvent(Event $event): int
    {
        // Validate Event object
        if (empty($event->getTitle()) || empty($event->getEventDate())) {
            throw new \InvalidArgumentException('Title and event date are required.');
        }

        return $this->eventRepository->createEvent($event);
    }

    public function deleteEvent(int $eventId): void
    {
        if ($eventId <= 0) {
            throw new \InvalidArgumentException('Invalid event ID.');
        }

        $this->eventRepository->deleteEvent($eventId);
    }

    public function createEventOrganization(
        $eventId,
        $organizerId,
        $organizerPaymentDetails = null,
        ?string $placeAddress = null,
        ?bool $isAnonymous = false,
        ?int $excludedUserName = null
    ): void {
        // Validate event ID
        if ($eventId <= 0) {
            throw new \InvalidArgumentException('Invalid event ID.');
        }

        $this->eventOrganizationRepository->createEventOrganization(
            $eventId,
            $organizerId,
            $organizerPaymentDetails,
            $placeAddress,
            $isAnonymous,
            $excludedUserName
        );

        //add the organizer as the first particiapnt
        $this -> includeParticipants($eventId, [$organizerId]);
        
        $this -> setHasOrganization($eventId, true);
    }

    public function includeParticipants($eventId, array $userIds): void
    {
        // Validate event ID and participants
        if ($eventId <= 0) {
            throw new \InvalidArgumentException('Invalid event ID.');
        }

        if (empty($userIds)) {
            throw new \InvalidArgumentException('Participants list cannot be empty.');
        }
        foreach ($userIds as $userId) {
            if ($userId <= 0) {
                throw new \InvalidArgumentException('Invalid user ID: ' . $userId);
            }

            $this->participantsRepository->addParticipant($eventId, $userId);
        }
    }

    public function makeEventAnonymous($eventId, $isAnonymous, $excludedUserName): void
    {
        if ($eventId <= 0) {
            throw new \InvalidArgumentException('Invalid event ID.');
        }

        // Update anonymity details for the event
        $this->eventOrganizationRepository-> updateEventAnonymity($eventId, $isAnonymous, $excludedUserName);
    }

    public function updatePlaceAddress(int $eventId, string $placeAddress): void
    {
        if ($eventId <= 0) {
            throw new \InvalidArgumentException('Invalid event ID.');
        }

        if (empty($placeAddress)) {
            throw new \InvalidArgumentException('Place address cannot be empty.');
        }

        // Update the place address for the event
        $this->eventOrganizationRepository->updatePlaceAddress($eventId, $placeAddress);
    }

    public function getEventById(int $eventId): ?Event
    {
        //simulating function
        //
        // Примерен обект за Event
        // $exampleEvent = new Event(
        //     1,                      // ID на събитието
        //     'Company Meeting',      // Заглавие на събитието
        //     '2025-03-15',           // Дата на събитието
        //     'Meeting',              // Тип на събитието (например "Meeting")
        //     'private',              // Видимост на събитието (например "private")
        //     true                    // Флаг за организация (например true, ако има организация)
        // );
        // return $exampleEvent;
        return $this->eventRepository->getEventById($eventId);
    }

    public function getParticipants(int $eventId): array
    {
        if ($eventId <= 0) {
            throw new \InvalidArgumentException('Invalid event ID.');
        }
        //simulating function
        return $this->participantsRepository->getParticipants($eventId);
    }

    public function getEventOrganization(int $eventId): ?array
    {
        if ($eventId <= 0) {
            throw new \InvalidArgumentException('Invalid event ID.');
        }

        //simulating function
        // return [];
        return $this->eventOrganizationRepository->getEventOrganization($eventId);
    }
    public function getAllEvents(): array
    {
        return $this->eventRepository->getAllEvents();
    }
    
    public function setHasOrganization(int $eventId, bool $hasOrganization): void
    {
        // Проверка на валидността на ID
        if ($eventId <= 0) {
            throw new \InvalidArgumentException('Invalid event ID.');
        }

        // Актуализация на полето в базата данни
        $this->eventRepository->updateHasOrganization($eventId, $hasOrganization);
    }

}
