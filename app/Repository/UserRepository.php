<?php

namespace App\Repository;

use App\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct(User $user, $searchColumns = ['username'], $selects = [])
    {
        parent::__construct($user, $searchColumns, $selects);
    }
}
