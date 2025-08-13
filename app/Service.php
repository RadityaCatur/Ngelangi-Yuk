<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Service extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'services';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'price',
        'kuota',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getCategoryAttribute()
    {
        // Ambil bagian pertama dari nama, misalnya "Kelas Anak", "Kelas Dewasa", dll.
        if (Str::startsWith($this->name, 'Kelas Anak Anak')) {
            return 'Kelas Anak Anak';
        } elseif (Str::startsWith($this->name, 'Kelas Dewasa')) {
            return 'Kelas Dewasa';
        } elseif (Str::startsWith($this->name, 'Kelas Terapi / Home Visit')) {
            return 'Kelas Terapi / Home Visit';
        } elseif (Str::startsWith($this->name, 'Testing_Paket_Latihan')) {
            return 'Kelas Testing';
        }

        return 'Lainnya';
    }


    public function clients()
    {
        return $this->belongsToMany(Client::class);
    }

    public function appointments()
    {
        return $this->belongsToMany(Appointment::class);
    }
}
