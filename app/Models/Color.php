<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;
    // editable columns
    protected $fillable = ["name", "color", "pantone_value", "year"];
}
