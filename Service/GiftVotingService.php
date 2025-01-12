<?php

namespace Service;

use Repository\GiftRepository;
use Repository\VoteRepository;

class GiftVotingService
{
    private $giftRepository;
    private $voteRepository;

    public function __construct()
    {
        $this->giftRepository = new GiftRepository();
        $this->voteRepository = new VoteRepository();
    }

    public function getGiftsByEvent($eventId)
    {
        return $this->giftRepository->getGiftsByEvent($eventId);
    }

    public function addGift($eventId, $giftName, $giftPrice)
    {
        return $this->giftRepository->addGift($eventId, $giftName, $giftPrice);
    }

    public function voteForGift($giftId, $userId)
    {
        return $this->voteRepository->addVote($giftId, $userId);
    }

    public function getUserVote($eventId, $userId)
    {
        return $this->voteRepository->getUserVoteByEvent($eventId, $userId);
    }

    public function getWinningGift($eventId)
    {
        return $this->giftRepository->getWinningGift($eventId);
    }
}