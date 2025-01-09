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
        int $eventId,
        int $organizerId = null,
        string $organizerPaymentDetails = null,
        ?string $placeAddress = null,
        ?bool $isAnonymous = false,
        ?int $excludedUserId = null
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
            $excludedUserId
        );

        //add the organizer as the first particiapnt
        $this -> includeParticipants($eventId, [$$organizerId]);
    }

    public function includeParticipants(int $eventId, array $userIds): void
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

    public function makeEventAnonymous(int $eventId, bool $isAnonymous, int $excludedUserId): void
    {
        if ($eventId <= 0) {
            throw new \InvalidArgumentException('Invalid event ID.');
        }

        // Update anonymity details for the event
        $this->eventOrganizationRepository-> updateEventAnonymity($eventId, $isAnonymous, $excludedUserId);
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


}
