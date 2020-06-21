<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

class HelpDeskCategory extends Model
{
    use Actionable, SoftDeletes;

    /**
     * A Category can Hava many Helpdesk
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function helpdesks(): HasMany
    {
        return $this->hasMany(HelpDesk::class, 'help_desk_category_id');
    }
}
