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
        require_once 'View/templates/signUpForm.phtml';
    }
    public function signIn()
    {
        require_once 'View/templates/signInForm.phtml';
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
        $session = sessionhandler::getinstance();
        
        $username = $session->getSessionValue('username');
        
        $following = $this->userService->getFollowingUsernames($username);
        
        $hiddenFollowingEvents = $this->eventService->getHiddenEventsByFollowingUsers($following);
        $participantFollowerEvents  = $this->eventService->getEventsWhereNotHiddenAndFollowingParticipate($username, $following);
        $allNotHiddenEvents = $this->eventService->getAllNotHiddenEvents($username);
        $organizationEvents = $this->eventService->getEventsWithOrganizationAndNotHidden($username);

        require_once  'View/templates/event_list.phtml';
    }

    public function search(){
        require_once 'View/templates/search.phtml';
    }
    public function viewProfile(){
        $session = SessionHandler::getInstance();
        $this->userService->populateUser($session->getSessionValue('userId'));
        require_once 'View/templates/viewProfile.phtml';
    }
    public function myProfile(){
        $session = SessionHandler::getInstance();
        $this->userService->populateUser($session->getSessionValue('userId'));
        $this->userService->loadFollowLinks();
        require_once 'View/templates/myProfile.phtml';
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

        $participants = $this->eventService->getParticipants($eventId);

        // Fetch comments
        $comments = $this->commentService->getCommentsByTarget($eventId, 'event');

        // Check if the user is a participant
        $isParticipant = $this->eventService->isParticipant($userId, $eventId);

        // Fetch organization details if the event has an organization
        $organization = null;
        $organizerName = null;
        if ($event->getHasOrganization()) {
            $organization = $this->eventService->getEventOrganization($eventId);
            $organizerName =$this->userService->getUsernameById($organization['organizer_id']);
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
    public function about(){
        require_once 'View/templates/about.phtml';
    }


}
