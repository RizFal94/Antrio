<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CustomerService;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'customer_service_id',
        'tanggal',
        'urutan',
        'dilayani',
        'skip',
    ];

    protected $casts = [
        'dilayani' => 'boolean',
        'skip' => 'boolean',
    ];

    public function customerService()
    {
        return $this->belongsTo(CustomerService::class);
    }

    public function service()
    {
        return $this->customerService->service();
    }

    public function layanan()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
