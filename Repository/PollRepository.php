<?php 

namespace Repository;

class PollRepository extends BaseRepository {

    // Create a new poll
    public function createPoll($eventId, $duration)
    {
        $sql = "
            INSERT INTO polls (event_id, ends_at)
            VALUES (:event_id, DATE_ADD(NOW(), INTERVAL :duration MINUTE))
        ";
        $params = [
            ':event_id' => $eventId,
            ':duration' => $duration,
        ];

        $this->executeQuery($sql, $params);
    }

    // Check if a poll exists and is active
    public function hasPoll($eventId)
    {
        $sql = "
            SELECT COUNT(*) as count
            FROM polls
            WHERE event_id = :event_id AND hasEnded = 0
        ";
        $params = [':event_id' => $eventId];
    
        $result = $this->fetchOne($sql, $params);
        return $result['count'] > 0;
    }

    // Mark a poll as ended
    public function endPoll($eventId)
    {
        $sql = "
            UPDATE polls
            SET hasEnded = 1
            WHERE event_id = :event_id
        ";
        $params = [':event_id' => $eventId];
        $this->executeQuery($sql, $params);
    }

    public function hasPollEnded($eventId)
    {
        $sql = "
            SELECT hasEnded
            FROM polls
            WHERE event_id = :event_id
            LIMIT 1
        ";
        $params = [':event_id' => $eventId];

        $result = $this->fetchOne($sql, $params);

        // Проверяваме дали е намерен запис и връщаме стойността на hasEnded
        return $result ? (bool)$result['hasEnded'] : null;
    }

}