<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\MediaLibrary;
use Kyslik\ColumnSortable\Sortable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Property extends Model
{
    use HasFactory;
    use Sluggable;

    protected $fillable = [
        'property_code',
        'listing_title',
        'property_type_id',
        'listingprice',
        'currency_iso_code',
        'listingtype',
        'lotsize',
        'property_number',
        'totalarea',
        'description',
        'fullbathrooms',
        'bedrooms',
        'address',
        'latitude',
        'longitude',
        'property_status_prov',
        'mode',
        'property_created',
        'property_updated',
        'city_id',
        'agent_id',
        'images',
        'property_finder_region',
        'community_id',
        'view360'
    ];

    protected $casts = [
        'images' => 'array',
        'view360' => 'array'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'listing_title'
            ]
        ];
    }

    /**
     * Get the propertyType that owns the Property
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function propertyType(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class);
    }

    /**
     * Get the agent that owns the Property
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }
}
