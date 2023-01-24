<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PropertyType extends Model
{
    use HasFactory;
    use Sluggable;

    protected $fillable = ['title','is_commercial','is_residential'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
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

    /**
     * Get the properties associated with the PropertyType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class,'project_property_types');
    }
}
