<?php

namespace Service;

use Repository\GiftRepository;
use Repository\VoteRepository;
use Repository\PollRepository;

class GiftVotingService
{
    private $giftRepository;
    private $voteRepository;

    private $pollRepository;

    public function __construct()
    {
        $this->giftRepository = new GiftRepository();
        $this->voteRepository = new VoteRepository();
        $this->pollRepository = new PollRepository();
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

    public function changeVote($giftId, $userId)
    {
        $this->voteRepository->updateUserVote($giftId, $userId);
    }

    public function createPoll($eventId, $duration)
    {
        $this->pollRepository->createPoll($eventId, $duration);
    }
    
    public function hasPoll($eventId)
    {
        return $this->pollRepository->hasPoll($eventId);
    }

    public function hasPollEnded($eventId)
    {
        return $this->pollRepository->hasPollEnded($eventId);
    }

    public function endPoll($eventId)
    {
        $this->pollRepository->endPoll($eventId);
    }


}