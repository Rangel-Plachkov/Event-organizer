<?php
namespace Controller;

use Service\EventService;
use Service\CommentService;

use Entity\Comment;
use Service\GiftVotingService;

class EventDashboardController
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

    public function showEventDashboard()
    {
        $eventId = $_POST['eventId'] ?? null;
        
        $userId = 1;
        // Fetch event information
        $event = $this->eventService->getEventById($eventId);
        if (!$event) {
            throw new \InvalidArgumentException('Event not found.');
        }

        // Fetch participants
        $participantsIds = $this->eventService->getParticipants($eventId);
        
        // Fetch comments
        $comments = $this->commentService->getCommentsByTarget($eventId, 'event');

        // Check if the user is a participant
        $isParticipant = in_array($userId, $participantsIds);

        // Fetch organization details if the event has an organization
        $organization = null;
        if ($event->getHasOrganization()) {
            $organization = $this->eventService->getEventOrganization($eventId);
        }
        
        //Gift poll variables
        $hasPoll = $this->giftVotingService->hasPoll($eventId);
        $pollEnded = $this->giftVotingService->hasPollEnded($eventId);
        $winningGift = $this->giftVotingService->getWinningGift($eventId);
        $gifts = $this->giftVotingService->getGiftsByEvent($eventId);

        // Check if the user has allready voted 
        $userVote = $this->giftVotingService->getUserVote($eventId, $userId);
 

        // Render the dashboard view
        include 'View/templates/event_dashboard.phtml';
        
        // require_once 'View/templates/event_dashboard.phtml';
    }

    public function createComment()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            $commentContent = trim($data['comment'] ?? '');
            $eventId = trim($data['eventId'] ?? '');
            // $eventId = $_POST['eventId'] ?? null;
            $targetId = $eventId;
            $targetType = 'event';

            $userId = 1; // Тук трябва да бъде ID на потребителя от сесията (или статично за тест)

            if (empty($commentContent)) {
                throw new \InvalidArgumentException('Comment content cannot be empty.');
            }

            $comment = new Comment(
                null,         
                $targetId,      
                $targetType,   
                $userId,      
                $commentContent
            );

            $this->commentService->createComment($comment);

            // Return answer to javascript
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'message' => 'Comment added successfully.',
                'userId' => $userId,
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
            // Вземане на данните от POST заявката
            $data = json_decode(file_get_contents('php://input'), true);
    
            $eventId = $data['eventId'] ?? null;
            //TODO: със сессия
            $organizerId = 1; // Тук трябва да се използва ID на потребителя от сесията (примерно)
            $organizerPaymentDetails = $data['organizer_payment_details'] ?? null;
            $placeAddress = $data['place_address'] ?? null;
            $isAnonymous = $data['is_anonymous'] ?? false;
            $excludedUserId = $data['excluded_user_id'] ?? null;

    
            // Валидация на входните данни
            if (!$eventId || !$placeAddress) {
                throw new \InvalidArgumentException('Event ID and place address are required.');
            }
    
            // Създаване на организацията чрез EventService
            $this->eventService->createEventOrganization(
                $eventId,
                $organizerId,
                $organizerPaymentDetails,
                $placeAddress,
                $isAnonymous,
                $excludedUserId
            );
    
            // Връщане на успешен JSON отговор
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'message' => 'Organization added successfully!',
            ]);
        } catch (\Exception $e) {
            // Връщане на грешка в JSON отговор
            header('Content-Type: application/json', true, 400);
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
        exit;
    }
    
    // Add New Gift
    public function addGift()
    {
        header('Content-Type: application/json');
        try {

            $eventId = trim($_POST['eventId'] ?? '');
            // $eventId = 1; // Тестово ID
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
        // Вземане на JSON данните
        $data = json_decode(file_get_contents('php://input'), true);
    
        if (!isset($data['giftId']) || empty($data['giftId'])) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid gift ID.']);
            exit;
        }
    
        $giftId = (int)$data['giftId'];
        //Todo: change for id with session
        $userId = 1; // Примерен ID, вземи го от сесията
    
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

        //TODO: temp remove later
        // $data['eventId'] = 1;

        if (empty($data['eventId']) || empty($data['duration'])) {
            echo json_encode(['status' => 'error', 'message' => 'Event ID and duration are required.']);
            exit;
        }

        try {
            $eventId = $data['eventId'];
            $duration = $data['duration'];

            $this->giftVotingService->createPoll($eventId, $duration);

            echo json_encode(['status' => 'success', 'message' => 'Poll created successfully.']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function endPoll()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // $eventId = 1; //TODO: TMP, to remove
    
        if (empty($data['eventId'])) {
            echo json_encode(['status' => 'error', 'message' => 'Event ID is required.']);
            exit;
        }
    
        try {
            $eventId = $data['eventId'];
    
            // Приключване на poll-a
            $this->giftVotingService->endPoll($eventId);
    
            // Вземане на победителя
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
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }
    
}