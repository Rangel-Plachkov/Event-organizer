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
        // $eventId = 46;
        // $eventId = $id;
        $eventId = $_GET['id'] ?? null;
        dd($eventId);
        $userId = 1;
        // Fetch event information
        $event = $this->eventService->getEventById($eventId);
        if (!$event) {
            throw new \InvalidArgumentException('Event not found.');
        }

        // Fetch participants
        $participantsIds = $this->eventService->getParticipants($eventId);
        // $participantsIds = [];
        // dd($participants);
        // $participants = ["toni"];
        
        // Fetch comments
        $comments = $this->commentService->getCommentsByTarget($eventId, 'event');

        // Check if the user is a participant
        // $isParticipant = in_array($userId, array_column($participants, 'user_id'));
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
            $targetId = 46; // Текущото ID на събитието (примерно)
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

}