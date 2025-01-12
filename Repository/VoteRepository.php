<?php
namespace Repository;

class VoteRepository extends BaseRepository
{
    // Add a vote for a gift
    public function addVote($giftId, $userId)
    {
        $sql = "INSERT INTO votes (gift_id, user_id) VALUES (:gift_id, :user_id)";
        $params = [
            ':gift_id' => $giftId,
            ':user_id' => $userId
        ];

        $this->executeQuery($sql, $params);
    }

    // Get the total votes for each gift in an event
    public function getVotesByEvent($eventId)
    {
        $sql = "
            SELECT g.id as gift_id, g.gift_name, g.gift_price, COUNT(v.id) as vote_count
            FROM gifts g
            LEFT JOIN votes v ON g.id = v.gift_id
            WHERE g.event_id = :event_id
            GROUP BY g.id, g.gift_name, g.gift_price
            ORDER BY vote_count DESC
        ";
        $params = [':event_id' => $eventId];

        return $this->fetchAll($sql, $params);
    }

    // Check if a user has already voted for a specific gift
    public function hasUserVoted($giftId, $userId)
    {
        $sql = "SELECT COUNT(*) as count FROM votes WHERE gift_id = :gift_id AND user_id = :user_id";
        $params = [
            ':gift_id' => $giftId,
            ':user_id' => $userId
        ];

        $result = $this->fetchOne($sql, $params);
        return $result['count'] > 0;
    }

    // Get the user's vote for an event
    public function getUserVoteByEvent($eventId, $userId)
    {
        $sql = "SELECT g.id AS gift_id, g.gift_name, g.gift_price
                FROM votes v
                JOIN gifts g ON v.gift_id = g.id
                WHERE g.event_id = :event_id AND v.user_id = :user_id";

        $params = [
            ':event_id' => $eventId,
            ':user_id' => $userId,
        ];

        $result = $this->fetchOne($sql, $params);

        if ($result) {
            return [
                'gift_id' => $result['gift_id'],
                'gift_name' => $result['gift_name'],
                'gift_price' => $result['gift_price'],
            ];
        }

        return null; // If the user hasn't voted
    }
    public function updateUserVote($giftId, $userId)
    {
        // Delete the prev vote
        $sqlDelete = "DELETE FROM votes WHERE user_id = :user_id";
        $this->executeQuery($sqlDelete, [':user_id' => $userId]);

        // add new vote
        $sqlInsert = "INSERT INTO votes (gift_id, user_id) VALUES (:gift_id, :user_id)";
        $params = [
            ':gift_id' => $giftId,
            ':user_id' => $userId
        ];
        $this->executeQuery($sqlInsert, $params);
    }

}
