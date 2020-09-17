<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Procurement extends Model implements HasMedia
{
    use InteractsWithMedia;

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'date_of_problem'       => 'date',
        'date_of_problem_fixed' => 'date',
    ];

    /**
     * @var string[] $type
     */
    public static $type = [
        'corrective' => 'Corrective',
        'preventive' => 'Preventive',
    ];

    /**
     * BelongsTo Equipments
     * 
     * @return BelongsTo
     */
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(BuildingEquipment::class, 'building_equipment_id');
    }

    /**
     * Get formatted date date_of_problem attribute.
     *
     * @return mixed
     */
    public function getFormattedProblemAttribute()
    {
        return $this->date_of_problem->format('d F Y');
    }

    /**
     * Get Formatted date_of_problem_fixed Attribute
     *
     * @return mixed
     */
    public function getFormattedFixedAttribute()
    {
        return $this->date_of_problem_fixed->format('d F Y');
    }

    /**
     * Get formatted LWBP rate
     *
     * @return mixed
     */
    public function getFormattedCostAttribute()
    {
        return 'Rp. ' . number_format($this->cost);
    }

    /**
     * An equipment belongs to one category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(BuildingHelpDeskCategory::class, 'help_desk_category_id');
    }

    /**
     * Register the media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->registerMediaConversions(function () {
                $this->addMediaConversion('tiny')
                    ->fit(Manipulations::FIT_CROP, 75, 75)
                    ->performOnCollections('avatar')
                    ->nonQueued();

                $this->addMediaConversion('small')
                    ->fit(Manipulations::FIT_CROP, 150, 150)
                    ->performOnCollections('avatar')
                    ->nonQueued();

                $this->addMediaConversion('medium')
                    ->fit(Manipulations::FIT_CROP, 300, 300)
                    ->performOnCollections('avatar')
                    ->nonQueued();

                $this->addMediaConversion('large')
                    ->fit(Manipulations::FIT_CROP, 600, 600)
                    ->performOnCollections('avatar')
                    ->nonQueued();
            });
    }
}
