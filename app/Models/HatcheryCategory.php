<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HatcheryCategory extends Model
{
    use HasFactory;

        protected $table = "hatchery_categories";

    protected $guarded = [];
}
