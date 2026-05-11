<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    // Menentukan kolom apa saja yang boleh diisi secara massal (Mass Assignment)
    protected $fillable = [
        'booking_code',
        'user_id',
        'studio_id',
        'booking_date',
        'start_time',
        'end_time',
        'payment_method',
        'total_price',
        'payment_proof',
        'notes',
        'status'
    ];

    // Mengubah format data saat diambil dari database (Casting)
    protected $casts = [
        'booking_date' => 'date',
    ];
    
    // Relasi: Satu booking dimiliki oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Satu booking terkait dengan satu Studio
    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }

    // Menghitung jumlah sesi otomatis berdasarkan waktu mulai dan selesai
    public function getJumlahSesiAttribute()
    {
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);
        
        $durasiMenit = $start->diffInMinutes($end);
        
        // Asumsi: 1 sesi = 5 menit
        $jumlahSesi = $durasiMenit / 5;

        // Memastikan minimal terhitung 1 sesi jika durasi sangat singkat
        return $jumlahSesi > 0 ? $jumlahSesi : 1;
    }
}