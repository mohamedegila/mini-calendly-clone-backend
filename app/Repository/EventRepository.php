<?php

namespace App\Repository;

use App\Models\Event;

class EventRepository extends BaseRepository
{
    public function __construct(Event $event, $searchColumns = ['name'], $selects = [])
    {
        parent::__construct($event, $searchColumns, $selects);
    }
}
