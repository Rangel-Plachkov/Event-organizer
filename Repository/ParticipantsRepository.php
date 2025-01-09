<?php

namespace Repository;

class ParticipantsRepository extends BaseRepository
{
    // the repository should have auto delete fucntionality
    // because of the sql's "ON DELETE CASCADE"

    public function addParticipant(int $eventId, int $userId): void
    {
        $sql = "INSERT INTO participants (event_id, user_id) 
                VALUES (:event_id, :user_id)";

        $params = [
            ':event_id' => $eventId,
            ':user_id' => $userId,
        ];

        $this->executeQuery($sql, $params);
    }
}
