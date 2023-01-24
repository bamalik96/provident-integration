<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Kyslik\ColumnSortable\Sortable;
use Cviebrock\EloquentSluggable\Sluggable;


class AgentCategory extends Model  implements Auditable
{
    use HasFactory;
    use Sluggable;
    use \OwenIt\Auditing\Auditable;
    use Sortable;

    protected $fillable = ['name'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
