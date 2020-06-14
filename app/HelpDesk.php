<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HelpDesk extends Model
{
    /**
     * Get formatted date attribute.
     *
     * @return mixed
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d F Y');
    }

    /**
     * {@inheritDoc}
     */
    public static $statuses = [
        'pending' => 'Pending',
        'in-progress' => 'In progress',
        'done' => 'done'
    ];

    /**
     * A Help desk can have one Category 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(HelpDeskCategory::class, 'help_desk_category_id');
    }
    
    /**
     * A Helpdesk belongsTo Building
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    /**
     * A Helpdesk Belongs To User
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
