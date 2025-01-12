<?php

namespace Repository;

class EventOrganizationRepository extends BaseRepository
{
    // the repository should have auto delete fucntionality
    // because of the sql's "ON DELETE CASCADE"


    public function createEventOrganization(
        $eventId,
        $organizerId,
        $organizerPaymentDetails = null,
        ?string $placeAddress = null,
        ?bool $isAnonymous = false,
        ?int $excludedUserId = null
    ): void {
        $sql = "INSERT INTO event_organization (
                    event_id, organizer_id, organizer_payment_details,place_address, is_anonymous, excluded_user_id
                )
                VALUES (:event_id, :organizer_id, :organizer_payment_details, :place_address, :is_anonymous,
                :excluded_user_id)";

        $params = [
            ':event_id' => $eventId,
            ':organizer_id' => $organizerId,
            ':organizer_payment_details' => $organizerPaymentDetails,
            ':is_anonymous' => $isAnonymous ? 1 : 0,
            ':excluded_user_id' => $excludedUserId,
            ':place_address' => $placeAddress,
        ];

        $this->executeQuery($sql, $params);
    }

    public function updateEventAnonymity(int $eventId, bool $isAnonymous, int $excludedUserId): void
    {
        $sql = "UPDATE event_organization 
            SET is_anonymous = :is_anonymous, excluded_user_id = :excluded_user_id 
            WHERE event_id = :event_id";

        $params = [
            ':is_anonymous' => $isAnonymous ? 1 : 0,
            ':excluded_user_id' => $excludedUserId,
            ':event_id' => $eventId,
        ];

        $this->executeQuery($sql, $params);
    }

    public function updatePlaceAddress(int $eventId, string $placeAddress): void
    {
        $sql = "UPDATE event_organization 
            SET place_address = :place_address 
            WHERE event_id = :event_id";

        $params = [
            ':place_address' => $placeAddress,
            ':event_id' => $eventId,
        ];

        $this->executeQuery($sql, $params);
    }

    public function getEventOrganization(int $eventId): ?array
    {
        $sql = "SELECT * FROM event_organization WHERE event_id = :event_id";
    
        $params = [':event_id' => $eventId];
    
        $result = $this->fetchOne($sql, $params);
    
        return $result ?: null;
    }
    
}
