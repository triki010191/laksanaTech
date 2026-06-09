<?php

namespace App\Models;

use App\Traits\HasPublicUuid;
use Illuminate\Database\Eloquent\Model;

class BlogTag extends Model
{
    use HasPublicUuid;

    protected $fillable = ['name', 'slug'];
}
