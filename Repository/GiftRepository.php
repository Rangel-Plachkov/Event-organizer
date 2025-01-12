<?php
namespace Repository;


class GiftRepository extends BaseRepository
{
    // Add a new gift to the database
    public function addGift($eventId, $name, $price)
    {
        $sql = "INSERT INTO gifts (event_id, name, price) VALUES (:event_id, :name, :price)";
        $params = [
            ':event_id' => $eventId,
            ':name' => $name,
            ':price' => $price
        ];
        $this->executeQuery($sql, $params);

        return $this->connection->lastInsertId();
    }

    // Get all gifts for a specific event
    public function getGiftsByEvent($eventId)
    {
        $sql = "SELECT * FROM gifts WHERE event_id = :event_id";
        $params = [':event_id' => $eventId];

        return $this->fetchAll($sql, $params);
    }

    public function getWinningGift($eventId)
    {
        $sql = "SELECT g.id AS gift_id, g.gift_name, g.gift_price, COUNT(v.id) AS vote_count
                FROM gifts g
                LEFT JOIN votes v ON g.id = v.gift_id
                WHERE g.event_id = :event_id
                GROUP BY g.id
                ORDER BY vote_count DESC
                LIMIT 1";
    
        $params = [
            ':event_id' => $eventId,
        ];
    
        // Проверете дали имате метод fetchOne() или подобен
        $result = $this->fetchOne($sql, $params); // Заменете fetch с fetchOne или аналогичен метод
    
        if ($result) {
            return [
                'gift_id' => $result['gift_id'],
                'name' => $result['name'],
                'price' => $result['price'],
                'vote_count' => $result['vote_count'],
            ];
        }
    
        return null; // Ако няма гласуване
    }
    
}