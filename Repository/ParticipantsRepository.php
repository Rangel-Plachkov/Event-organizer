<?php

namespace Repository;

class ParticipantsRepository extends BaseRepository
{
    // the repository should have auto delete fucntionality
    // because of the sql's "ON DELETE CASCADE"

    public function addParticipant($eventId, $userId): void
    {
        $sql = "INSERT INTO participants (event_id, user_id) 
                VALUES (:event_id, :user_id)";

        $params = [
            ':event_id' => $eventId,
            ':user_id' => $userId,
        ];

        $this->executeQuery($sql, $params);
    }
    public function getParticipantsIds(int $eventId): array
    {
        $sql = "SELECT user_id FROM participants WHERE event_id = :event_id";
    
        $params = [':event_id' => $eventId];
    
        $rows = $this->fetchAll($sql, $params);
    
        $participants = [];
        foreach ($rows as $row) {
            $participants[] = $row['user_id'];
        }
    
        return $participants;
    }
    
    public function isParticipant(int $userId, int $eventId): bool
    {
        $sql = "SELECT COUNT(*) as count FROM participants WHERE event_id = :event_id AND user_id = :user_id";
        
        $params = [
            ':event_id' => $eventId,
            ':user_id' => $userId,
        ];
        
        $result = $this->fetchOne($sql, $params);

        return $result['count'] > 0;
    }
}
