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
    public function getEventById(int $eventId): ?Event
    {
    $sql = "SELECT * FROM events WHERE id = :id";
    $params = [':id' => $eventId];

    $row = $this->fetchOne($sql, $params);

    if ($row) {
        return new Event(
            $row['id'],
            $row['title'],
            $row['event_date'],
            $row['type'],
            $row['visibility'],
            (bool)$row['has_organization']
        );
    }

    return null; // Return null if no event found
    }

    public function getAllEvents()
    {
        $sql = "SELECT * FROM events ORDER BY event_date ASC"; // Подреждане по дата на събитието
        $rows = $this->fetchAll($sql);

        $events = [];
        foreach ($rows as $row) {
            $events[] = new Event(
                $row['id'],
                $row['title'],
                $row['event_date'],
                $row['type'],
                $row['visibility'],
                $row['has_organization']
            );
        }

        return $events;
    }

    public function getHiddenEventsForUser(string $username): array
    {
        $sql = "SELECT e.* 
                FROM events e
                JOIN event_organization eo ON e.id = eo.event_id
                WHERE eo.excluded_user_name = :username";
    
        $params = [
            ':username' => $username,
        ];
    
        return $this->fetchAll($sql, $params);
    } 
}
