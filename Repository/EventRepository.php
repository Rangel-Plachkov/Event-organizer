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
}
