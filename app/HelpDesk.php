<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HelpDesk extends Model
{

    /**
     * {@inheritDoc}
     */
    public static $statuses = [
        'pending' => 'Pending',
        'in-progress' => 'In progress',
        'done' => 'done'
    ];
}
