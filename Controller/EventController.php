<?php

namespace Controller;

use Entity\Comment;
use Service\CommentService;
use Service\EventService;
use Service\GiftVotingService;
use Entity\Event;
use http\SessionHandler;

class EventController
{
    private EventService $eventService;
    private CommentService $commentService;
    private GiftVotingService $giftVotingService;

    public function __construct()
    {
        $this->eventService = new EventService();
        $this->commentService = new CommentService();
        $this->giftVotingService = new GiftVotingService();
    }

    public function createEvent()
    {
        header('Content-Type: application/json');
    
        try {
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
            $session = SessionHandler::getInstance();

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
                false
            );
    
            try {
                $eventId = $this->eventService->createEvent($event);
                
                // Return successful json
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Event created successfully.',
                    'eventId' => $eventId
                ]);
            } catch (\Exception $e) {
                $this->eventService->deleteEvent($eventId);
                // Retun json with
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to create event: ' . $e->getMessage()
                ]);
            }
        } catch (\Exception $e) {
            // Retun json with
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    
        exit;
    }
    
    public function createComment()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            // throw new \InvalidArgumentException('Im in the create comment function');

            $commentContent = trim($data['comment'] ?? '');
            $eventId = trim($data['eventId'] ?? '');
            $username = trim($data['username'] ?? '');
            $targetId = $eventId;
            $targetType = 'event';

            // $session = SessionHandler::getInstance();
            // $username = $session->getSessionValue('userId');

            if (empty($commentContent)) {
                throw new \InvalidArgumentException('Comment content cannot be empty.');
            }

            $comment = new Comment(
                null,         
                $targetId,      
                $targetType,   
                $username,      
                $commentContent
            );

            $this->commentService->createComment($comment);

            // Return answer to javascript
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'message' => 'Comment added successfully.',
                'username' => $username,
                'comment' => $commentContent,
            ]);
        } catch (\Exception $e) {
            
            // Return answer to javascript
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
        exit;
    }

    public function addOrganization()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
    
            $eventId = $data['eventId'] ?? null;

            $userId = $data['user_id'] ?? null;


            if ($userId === null) {
                throw new \InvalidArgumentException('user id should not be null:' + $userId);
            }

            $organizerId = $userId;

            $organizerPaymentDetails = $data['organizer_payment_details'] ?? null;
            $placeAddress = $data['place_address'] ?? null;
            $isAnonymous = $data['is_anonymous'] ?? false;
            $excludedUserId = $data['excluded_user_id'] ?? null;

    
            if (!$eventId || !$placeAddress) {
                throw new \InvalidArgumentException('Event ID and place address are required.');
            }
    
            $this->eventService->createEventOrganization(
                $eventId,
                $organizerId,
                $organizerPaymentDetails,
                $placeAddress,
                $isAnonymous,
                $excludedUserId
            );
    
            // Retturn successful answer
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'message' => 'Organization added successfully!',
            ]);
        } catch (\Exception $e) {
            // Return error
            header('Content-Type: application/json', true, 400);
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
        exit;
    }
    
    public function joinOrganization()
    {
        $eventId = $_POST['eventId'] ?? null;
        $userId = $_POST['userId'] ?? null;
        
        if (!$eventId || !$userId) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid event or user ID.']);
            exit;
        }

        try {
            $this->eventService->joinOrganization($eventId, $userId);

            echo json_encode(['status' => 'success', 'message' => 'Successfully joined the event.']);
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function addGift()
    {
        header('Content-Type: application/json');
        try {

            $eventId = trim($_POST['eventId'] ?? '');
            $giftName = trim($_POST['gift_name'] ?? '');
            $giftPrice = trim($_POST['gift_price'] ?? '');
    
            if (empty($giftName) || empty($giftPrice)) {
                throw new \InvalidArgumentException('Gift name and price are required.');
            }
    
            $newGiftId = $this->giftVotingService->addGift($eventId, $giftName, $giftPrice);
    
            echo json_encode([
                'status' => 'success',
                'message' => 'Gift added successfully!',
                'gift' => [
                    'id' => $newGiftId,
                    'gift_name' => $giftName,
                    'gift_price' => $giftPrice,
                ],
            ]);
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }
    
    public function voteForGift()
    {
        $data = json_decode(file_get_contents('php://input'), true);
    
        if (!isset($data['giftId']) || empty($data['giftId'])) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid gift ID.']);
            exit;
        }
    
        $giftId = (int)$data['giftId'];

        $session = SessionHandler::getInstance();
        $userId = $session->getSessionValue('userId');
    
        try {
            $this->giftVotingService->changeVote($giftId, $userId);
            echo json_encode(['status' => 'success', 'message' => 'Vote cast successfully']);
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }
     

    public function createPoll()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['eventId']) || empty($data['duration'])) {
            echo json_encode(['status' => 'error', 'message' => 'Event ID and duration are required.']);
            exit;
        }

        try {
            $eventId = $data['eventId'];
            $duration = $data['duration'];

            $this->giftVotingService->createPoll($eventId, $duration);

            echo json_encode(['status' => 'success', 'message' => 'Poll created successfully.']);
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function endPoll()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['eventId'])) {
            echo json_encode(['status' => 'error', 'message' => 'Event ID is required.']);
            exit;
        }
    
        try {
            $eventId = $data['eventId'];
    
            $this->giftVotingService->endPoll($eventId);
    
            $winner = $this->giftVotingService->getWinningGift($eventId);
    
            if ($winner) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Poll ended successfully.',
                    'winner' => $winner
                ]);
            } else {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Poll ended successfully, but no votes were cast.',
                    'winner' => null
                ]);
            }
        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }

}
