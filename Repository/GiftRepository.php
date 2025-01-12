<?php
namespace Repository;

class GiftRepository extends BaseRepository
{
    // Add a new gift to the database
    public function addGift($eventId, $name, $price)
    {
        $sql = "INSERT INTO gifts (event_id, gift_name, gift_price) VALUES (:event_id, :gift_name, :gift_price)";
        $params = [
            ':event_id' => $eventId,
            ':gift_name' => $name,
            ':gift_price' => $price
        ];
        $this->executeQuery($sql, $params);

        return $this->connection->lastInsertId();
    }

    // Get all gifts for a specific event
    public function getGiftsByEvent($eventId)
    {
        $sql = "SELECT id, gift_name, gift_price, created_at FROM gifts WHERE event_id = :event_id";
        $params = [':event_id' => $eventId];

        return $this->fetchAll($sql, $params);
    }

    // Get the winning gift for a specific event based on the number of votes
    public function getWinningGift($eventId)
    {
        $sql = "SELECT g.id AS gift_id, g.gift_name, g.gift_price, COUNT(v.id) AS vote_count
                FROM gifts g
                LEFT JOIN votes v ON g.id = v.gift_id
                WHERE g.event_id = :event_id
                GROUP BY g.id, g.gift_name, g.gift_price
                ORDER BY vote_count DESC
                LIMIT 1";

        $params = [
            ':event_id' => $eventId,
        ];

        $result = $this->fetchOne($sql, $params);

        if ($result) {
            return [
                'gift_id' => $result['gift_id'],
                'gift_name' => $result['gift_name'],
                'gift_price' => $result['gift_price'],
                'vote_count' => $result['vote_count'],
            ];
        }

        return null; // Ако няма подаръци или гласове
    }
}
