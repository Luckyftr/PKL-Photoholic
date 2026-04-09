<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'activity',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record($activity, $description = null)
    {
        self::create([
            'user_id' => auth()->id(),
            'activity' => $activity,
            'description' => $description,
        ]);
    }
}