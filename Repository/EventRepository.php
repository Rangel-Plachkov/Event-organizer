<?php

namespace Repository;

use Entity\Event;

class EventRepository extends BaseRepository
{
    public function createEvent(Event $event)
    {
        $sql = "INSERT INTO events (title, event_date, type, visibility, has_organization)
                VALUES (:title, :event_date, :type, :visibility, :has_organization)";

        $params = [
            ':title' => $event->getTitle(),
            ':event_date' => $event->getEventDate(),
            ':type' => $event->getType(),
            ':visibility' => $event->getVisibility(),
            ':has_organization' => $event->getHasOrganization() ? 1 : 0,
        ];
        //(alternative formating of date)':event_date' => $event->getEventDate()->format('Y-m-d H:i:s'),

        $this->executeQuery($sql, $params);

        // Връщаме ID на създадения запис
        return $this->connection->lastInsertId();
    }

    public function getPublicOrInvitedEvents(int $userId): array
    {
        $sql = "
            SELECT e.*, ei.status
            FROM events e
            LEFT JOIN event_invitations ei ON e.id = ei.event_id AND ei.user_id = :user_id
            WHERE e.visibility = 'public'
               OR (ei.user_id = :user_id AND ei.status = 'accepted')
        ";

        $params = [
            ':user_id' => $userId,
        ];

        return $this->fetchAll($sql, $params);
    }
}

//трябва да езема още евентите на приятелите на моя потребител
//за това ми трябва да знам как да взема userid на потребителя в текущата сесия.
