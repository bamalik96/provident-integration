<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Cviebrock\EloquentSluggable\Sluggable;

class Agent extends Model
{
    use HasFactory;
    use Sluggable;
    protected $casts = [
        'social_links' => 'array',
    ];

    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone_no',
        'office_no', 'whatsapp_no', 'description',
        'designation_id', 'category_id', 'license', 'social_links', 'status','slug'
    ];


    protected $appends = ['full_name'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' =>  ['first_name', 'last_name']
            ]
        ];
    }


    /**
     * Determine  full name of user
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get the properties associated with the PropertyType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function properties(): HasOne
    {
        return $this->hasOne(Property::class);
    }

    public function blog(): HasOne
    {
        return $this->hasOne(Blog::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
