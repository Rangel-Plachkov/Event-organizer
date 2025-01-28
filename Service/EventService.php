<?php

namespace Service;

use Repository\EventOrganizationRepository;
use Repository\EventRepository;
use Entity\Event;
use Repository\ParticipantsRepository;
use Repository\UserRepository;

class EventService
{
    private EventRepository $eventRepository;
    private EventOrganizationRepository $eventOrganizationRepository;
    private ParticipantsRepository $participantsRepository;
    private UserService $userService;
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->eventRepository = new EventRepository();
        $this->eventOrganizationRepository = new EventOrganizationRepository();
        $this->participantsRepository = new ParticipantsRepository();

        $this->userRepository = new UserRepository();
        $this->userService = new UserService();
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
        ?string $excludedUserName = null
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
        return $this->eventRepository->getEventById($eventId);
    }

    public function getParticipants(int $eventId): array
    {
        if ($eventId <= 0) {
            throw new \InvalidArgumentException('Invalid event ID.');
        }
        
        $participantsIds = $this->participantsRepository->getParticipantsIds($eventId);

        // Convert participant IDs to usernames
        $participants = [];
        foreach ($participantsIds as $userId) {
            $username = $this->userService->getUsernameById($userId);
            if ($username) {
                $participants[] = $username;
            }
        }       

        return $participants;
    }
    
    public function isParticipant($userId, $eventId)
    {

        return $this->participantsRepository->isParticipant($userId, $eventId);
        // return in_array($userId, $participantsIds);
    }

    public function getEventOrganization(int $eventId): ?array
    {
        if ($eventId <= 0) {
            throw new \InvalidArgumentException('Invalid event ID.');
        }

        return $this->eventOrganizationRepository->getEventOrganization($eventId);
    }
    public function getAllEvents(): array
    {
        return $this->eventRepository->getAllEvents();
    }
    
    public function getAllNotHiddenEvents(string $username): array
    {
        return $this->eventRepository->getAllNotHiddenEvents($username);
    }
    
    public function setHasOrganization(int $eventId, bool $hasOrganization): void
    {
        // Check event id validity
        if ($eventId <= 0) {
            throw new \InvalidArgumentException('Invalid event ID.');
        }

        $this->eventRepository->updateHasOrganization($eventId, $hasOrganization);
    }

    public function joinOrganization(int $eventId, int $userId): void
    {
        // Check of participant is allready added
        $participants = $this->participantsRepository->getParticipantsIds($eventId);
        if (in_array($userId, $participants, true)) {
            throw new \Exception('User is already a participant in this event.');
        }

        // Add participant
       $this->participantsRepository->addParticipant($eventId, $userId);
    }
    
    public function getHiddenEventsByFollowingUsers($following): array
    {
        $eventsByFollower = [];

        foreach ($following as $follower) {
            $events = $this->eventRepository->getHiddenEventsForUser($follower);
            if (!empty($events)) {
                $eventsByFollower[$follower] = $events;
            }
        }

        return $eventsByFollower;
    }

    public function getEventsWhereNotHiddenAndFollowingParticipate($username, $following) 
    {
        $eventsByFollower = [];

        foreach ($following as $follower) {

            $user = $this->userRepository->findUserByUsername($follower);
            
            if ($user) {

                $events = $this->eventRepository->getEventsWhereFollowerParticipateAndNotHidden($username, $user['id']);
                if (!empty($events)) {
                    $eventsByFollower[$follower] = $events;
                }
            }
        }

        return $eventsByFollower;
    }

    public function getEventsWithOrganizationAndNotHidden(string $username): array
    {
        return $this->eventRepository->getEventsWithOrganizationAndNotHidden($username);
    }

}
