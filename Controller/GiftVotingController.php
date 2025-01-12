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

    // Показва страницата за гласуване
    public function showGiftPoll()
    {
        //Fix when integrating into a page
        
        // $eventId = $_POST['eventId'] ?? null;
        $eventId = 46;

        if (!$eventId) {
            throw new \InvalidArgumentException('Event ID is required.');
        }

        $gifts = $this->giftVotingService->getGiftsByEvent($eventId);
        $userId = 1; // Текущият потребител (взет от сесия)

        // Проверка дали потребителят вече е гласувал
        $userVote = $this->giftVotingService->getUserVote($eventId, $userId);

        include 'View/templates/gift_poll.phtml';
    }

    // Добавя нов подарък
    public function addGift()
    {
        // $eventId = $_POST['eventId'];
        $eventId = 46;
        $giftName = trim($_POST['gift_name']);
        $giftPrice = trim($_POST['gift_price']);

        if (empty($giftName) || empty($giftPrice)) {
            throw new \InvalidArgumentException('Gift name and price are required.');
        }

        $this->giftVotingService->addGift($eventId, $giftName, $giftPrice);

        echo json_encode(['status' => 'success', 'message' => 'Gift added successfully']);
        exit;
    }

    // Гласува за подарък
    public function voteForGift()
    {
        $giftId = $_POST['giftId'];
        $userId = 1; // Взет от сесия

        $this->giftVotingService->voteForGift($giftId, $userId);

        echo json_encode(['status' => 'success', 'message' => 'Vote cast successfully']);
        exit;
    }

    // Затваря гласуването и показва победителя
    public function closePoll()
    {
        $eventId = $_POST['eventId'];

        $winningGift = $this->giftVotingService->getWinningGift($eventId);

        echo json_encode(['status' => 'success', 'winningGift' => $winningGift]);
        exit;
    }
}