<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_code',
        'user_id',
        'studio_id',
        'booking_date',
        'start_time',
        'end_time',
        'payment_method',
        'total_price',
        'notes',
        'status'
    ];

    protected $casts = [
        'booking_date' => 'date',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }

    public function getJumlahSesiAttribute()
    {
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);
        
        $durasiMenit = $start->diffInMinutes($end);
        $jumlahSesi = $durasiMenit / 5;

        return $jumlahSesi > 0 ? $jumlahSesi : 1;
    }

    // Catatan: Fungsi getTotalPriceAttribute() dihapus karena 
    // total_price sekarang sudah langsung diambil dari kolom database!
}