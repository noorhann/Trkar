<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Contracts\Activity;

class TicketCategory extends Model
{
    use HasFactory,SoftDeletes,LogsActivity;

    protected $fillable = [
        'name_ar',
        'name_en',
    ];
}
