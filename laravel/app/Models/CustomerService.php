<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Service;
use App\Models\Customer;

class CustomerService extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerServiceFactory> */
    use HasFactory;

    protected $table = 'customer_services';

    protected $fillable = [
        'user_id',
        'service_id',
        'name',
        'prefix',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
