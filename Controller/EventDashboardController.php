<?php
namespace Controller;

use Service\EventService;
use Service\CommentService;

use Entity\Comment;

class EventDashboardController
{
    private EventService $eventService;
    private CommentService $commentService;

    public function __construct()
    {
        $this->eventService = new EventService();
        $this->commentService = new CommentService();
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
            //TODO: със сессион
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
    
}