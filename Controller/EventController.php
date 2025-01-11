<?php

namespace Controller;

use Entity\Comment;
use Service\CommentService;
use Service\EventService;
use Entity\Event;
use http\SessionHandler;

class EventController
{
    private EventService $eventService;
    private CommentService $commentService;

    public function __construct()
    {
        $this->eventService = new EventService();
        $this->commentService = new CommentService();
    }

    // public function createEvent()
    // {
    //     $title = trim($_POST['title'] ?? '');
    //     $eventDate = trim($_POST['event_date'] ?? '');
    //
    //     // $type = trim($_POST['type'] ?? 'Other');
    //     $type = $_POST['type'];
    //
    //     // If other is selected add the custom type
    //     if ($type === 'other') {
    //         $customtype = trim($_POST['customtype'] ?? '');
    //         if (empty($customtype)) {
    //             throw new \InvalidArgumentException('моля, въведете тип за събитие.');
    //         }
    //         $type = $customtype;
    //     }
    //
    //     $visibility = trim($_POST['visibility'] ?? 'public');
    //     $hasOrganization = $_POST['has_organization'] === 'true';
    //     $organizerId = $_POST['organizer_id'] ?? null;
    //
    //     $isAnonymous = $_POST['is_anonymous'] === 'true';
    //     $excludedUserId = $_POST['excluded_users_id'] ?? '';
    //
    //     $organizationExplanation = null; // Default to null if no organization
    //
    //     // Handle organization explanation only if hasOrganization is true
    //     if ($hasOrganization) {
    //         $organizationExplanation = trim($_POST['explination-organisation'] ?? '');
    //         if (empty($organizationExplanation)) {
    //             throw new \InvalidArgumentException('Моля, добавете обяснение за организацията.');
    //         }
    //     }
    //
    //     if (empty($title) || empty($eventDate)) {
    //         throw new \InvalidArgumentException('Title and event date are required.');
    //     }
    //
    //     $event = new Event(
    //         null,
    //         $title,
    //         $eventDate,
    //         $type,
    //         $visibility,
    //         $hasOrganization,
    //         $organizerId,
    //         $isAnonymous,
    //         $excludedUserId
    //     );
    //
    //     try {
    //         $eventId = $this->eventService->createEvent($event);
    //
    //         // Save organization explanation as a comment if applicable
    //         if ($hasOrganization && !empty($organizationExplanation)) {
    //             $this->saveOrganizationExplanationAsComment($eventId, $organizationExplanation, $organizerId);
    //         }
    //
    //     } catch (\Exception $e) {
    //         throw $e;
    //     }
    //
    //     // // Пренасочване или съобщение за успех
    //     // header('Location: /events?id=' . $eventId);
    //     exit;
    // }

    public function createEvent()
    {
        $title = trim($_POST['title'] ?? '');
        $eventDate = trim($_POST['event_date'] ?? '');
        $type = trim($_POST['type'] ?? 'Other');

        if ($type === 'Other') {
            $customType = trim($_POST['customType'] ?? '');
            if (empty($customType)) {
                throw new \InvalidArgumentException('Please enter a custom type for the event.');
            }
            $type = $customType;
        }

        $visibility = trim($_POST['visibility'] ?? 'public');
        $hasOrganization = $_POST['has_organization'] === 'true';

        $session = SessionHandler::getInstance();
        $currUserId = $session->getSessionValue('userId');

        //TODO: test with active session
        $organizerId = '1'; // $$currUserId;

        $organizerPaymentDetails = trim($_POST['organizer_payment_details'] ?? '');
        $placeAddress = trim($_POST['place_address'] ?? '');

        $isAnonymous = $_POST['is_anonymous'] === 'true';
        $excludedUserName = $_POST['excluded_user_name'];

        //TODO: get the user id by the name
        $excludedUserId = '1';

        if (empty($title) || empty($eventDate)) {
            throw new \InvalidArgumentException('Title and event date are required.');
        }

        // Create the Event entity
        $event = new Event(
            null,
            $title,
            $eventDate,
            $type,
            $visibility,
            $hasOrganization
        );

        try {
            $eventId = $this->eventService->createEvent($event);

            if ($hasOrganization) {
                $this->eventService->createEventOrganization(
                    $eventId,
                    $organizerId,
                    $organizerPaymentDetails,
                    $placeAddress,
                    $isAnonymous,
                    $excludedUserId
                );
            }

        } catch (\Exception $e) {
            // Handle errors (log, display error messages, etc.)
            throw new \RuntimeException('Failed to create event: ' . $e->getMessage());
        }
    }

    private function saveOrganizationExplanationAsComment($eventId, $organizationExplanation, $userId)
    {
        if (empty($organizationExplanation)) {
            throw new \InvalidArgumentException('Explanation content cannot be empty.');
        }

        if (empty($eventId)) {
            throw new \InvalidArgumentException('Event ID cannot be empty.');
        }

        if (empty($userId)) {
            throw new \InvalidArgumentException('User ID cannot be empty.');
        }

        $comment = new Comment(
            null,
            $eventId,
            'event',
            $userId,
            $organizationExplanation
        );

        try {
            $this->commentService->createComment($comment);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to save explanation as comment: ' . $e->getMessage());
        }
    }
}
