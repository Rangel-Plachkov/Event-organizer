<?php

namespace Controller;

use Service\EventService;
use Entity\Event;

class EventController
{
    private EventService $eventService;

    public function __construct()
    {
        $this->eventService = new EventService();
    }

    public function createEvent()
    {
        $title = trim($_POST['title'] ?? '');
        $eventDate = trim($_POST['event_date'] ?? '');

        // $type = trim($_POST['type'] ?? 'Other');
        $type = $_POST['type'];

        // If other is selected add the custom type
        if ($type === 'other') {
            $customtype = trim($_POST['customtype'] ?? '');
            if (empty($customtype)) {
                throw new \InvalidArgumentException('моля, въведете тип за събитие.');
            }
            $type = $customtype;
        }

        $visibility = trim($_POST['visibility'] ?? 'public');
        $hasOrganization = $_POST['has_organization'] === 'true';
        //TODO: get organizer id
        $organizerId = $_POST['organizer_id'] ?? null;

        //TODO: anonumous always false
        $isAnonymous = $_POST['is_anonymous'] === 'true';
        //TODO: get excluded users id (this is an array)
        $excludedUserId = $_POST['excluded_users_id'] ?? '';

        //TODO: parce the comment also and maybe the other stuff

        if (empty($title) || empty($eventDate)) {
            throw new \InvalidArgumentException('Title and event date are required.');
        }

        $event = new Event(
            null,
            $title,
            $eventDate,
            $type,
            $visibility,
            $hasOrganization,
            $organizerId,
            $isAnonymous,
            $excludedUserId
        );

        try {
            $eventId = $this->eventService->createEvent($event);
        } catch (\Exception $e) {
            throw $e;
        }

        // // Пренасочване или съобщение за успех
        // header('Location: /events?id=' . $eventId);
        exit;
    }
}
