<?php

namespace Controller;

use Service\GiftVotingService;

class GiftVotingController
{
    private $giftVotingService;

    public function __construct()
    {
        $this->giftVotingService = new GiftVotingService();
    }

    public function showGiftPoll()
    {
        //Fix when integrating into a page
        
        $eventId = $_POST['eventId'] ?? null;
        // $eventId = 1;
        $hasPoll = $this->giftVotingService->hasPoll($eventId);
        $pollEnded = $this->giftVotingService->hasPollEnded($eventId);
        $winningGift = $this->giftVotingService->getWinningGift($eventId);

        if (!$eventId) {
            throw new \InvalidArgumentException('Event ID is required.');
        }

        $gifts = $this->giftVotingService->getGiftsByEvent($eventId);

        $session = SessionHandler::getInstance();
        $userId = $session->getSessionValue('userId');
        // $userId = 1; // Текущият потребител (взет от сесия)

        // Проверка дали потребителят вече е гласувал
        $userVote = $this->giftVotingService->getUserVote($eventId, $userId);

        include 'View/templates/gift_poll.phtml';
    }

    // Добавя нов подарък
    public function addGift()
    {
        header('Content-Type: application/json');
        try {

            // $eventId = 1; // Тестово ID
            $eventId = $_POST['eventId'] ?? null;
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
        // $userId = 1;
    
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