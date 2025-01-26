<?php

namespace Controller;

use http\SessionHandler;
use Service\CommentService;
use Service\GiftVotingService;
use Service\UserService;
use Service\EventService;

class PageController extends AbstractController
{
    private $userService;
    private $eventService;
    private $commentService;
    private $giftVotingService;


    public function __construct()
    {
        $this->userService = new UserService();
        $this->eventService = new EventService();
        $this->commentService = new CommentService();
        $this->giftVotingService = new GiftVotingService();
    }

    public function signUp()
    {
        require_once 'View/templates/signUpForm.html';
    }
    public function signIn()
    {
        require_once 'View/templates/signInForm.html';
    }
    public function edit()
    {
        $session = SessionHandler::getInstance();
        $this->userService->populateUser($session->getSessionValue('userId'));
        require_once 'View/templates/editProfile.phtml';
    }
    public function createEvent()
    {
        require_once 'View/templates/create_event_v2.html';
    }
        
    public function listEvents()
    {
        $events = $this->eventService->getAllEvents();

        $session = sessionhandler::getinstance();

        require_once  'View/templates/event_list.phtml';
    }

    public function eventDashboard()
    {
        $session = sessionhandler::getinstance();
        $userId = $session->getSessionValue('userId');

        $eventId = $_POST['eventId'] ?? null;

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
        
        require_once 'View/templates/event_dashboard.phtml';
    }
}
