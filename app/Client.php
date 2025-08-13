<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'clients';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'phone',
        'username',
        'user_id',
        'kuota',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
  
    public function appointments_clients()
    {
        return $this->belongsToMany(Appointment::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    public function categoryList()
    {
        return $this->services->map(function ($service) {
            return $service->category;
        })->unique()->values();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
