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
            SELECT g.id as gift_id, g.name, g.price, COUNT(v.id) as vote_count
            FROM gifts g
            LEFT JOIN votes v ON g.id = v.gift_id
            WHERE g.event_id = :event_id
            GROUP BY g.id, g.name, g.price
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

        // Проверете дали имате метод fetchOne() или подобен
        $result = $this->fetchOne($sql, $params); // Заменете fetch с fetchOne или аналогичен метод

        if ($result) {
            return [
                'gift_id' => $result['gift_id'],
                'name' => $result['name'],
                'price' => $result['price'],
            ];
        }

        return null; // Ако потребителят не е гласувал
    }

}