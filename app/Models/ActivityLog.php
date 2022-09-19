<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_log';

    protected $fillable = [
        'description',
        'subject_type',
        'subject_id',
        'causer_type',
        'causer_id',
        'properties',
        'batch_uuid',
    
    ];
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'causer_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'causer_id');
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'causer_id');
    }

}
