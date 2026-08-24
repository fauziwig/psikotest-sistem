<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    protected $fillable = [
        'company_name',
        'logo_path',
        'favicon_path',
        'primary_color',
        'secondary_color',
    ];
}
