<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Community extends Model
{
    use HasFactory;
    use Sluggable;

    protected $fillable = [
        'name', 'banner_title',
        'short_description', 'description', 'sale_button_text',
        'rent_button_text', 'properties_in_community_text',
        'location_that_inspire_you', 'transport_description', 'status',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }


    /**
     * Get the properties associated with the PropertyType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function property_types()
    {
        return $this->belongsToMany(Project::class);
    }
}
