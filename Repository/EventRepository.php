<?php

namespace Repository;

use Entity\Event;

class EventRepository extends BaseRepository
{
    public function createEvent(Event $event): int
    {
        $sql = "INSERT INTO events (title, event_date, type, visibility, has_organization)
                VALUES (:title, :event_date, :type, :visibility, :has_organization)";

        $params = [
            ':title' => $event->getTitle(),
            ':event_date' => $event->getEventDate(),
            ':type' => $event->getType(),
            ':visibility' => $event->getVisibility(),
            ':has_organization' => $event->getHasOrganization() ? 1 : 0
        ];

        $this->executeQuery($sql, $params);

        return $this->connection->lastInsertId();
    }

    public function deleteEvent(int $eventId): void
    {
        $sql = "DELETE FROM events WHERE id = :id";
        $params = [':id' => $eventId];

        $this->executeQuery($sql, $params);
    }

    public function updateHasOrganization(int $eventId, bool $hasOrganization): void
    {
        $sql = "UPDATE events SET has_organization = :has_organization WHERE id = :event_id";

        $params = [
            ':has_organization' => $hasOrganization ? 1 : 0,
            ':event_id' => $eventId,
        ];

        $this->executeQuery($sql, $params);
    }

}
