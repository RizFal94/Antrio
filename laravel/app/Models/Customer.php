<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'customer_service_id',
        'tanggal',
        'urutan',
        'terlayani',
        'skip',
    ];

    protected $casts = [
        'terlayani' => 'boolean',
        'skip' => 'boolean',
    ];

    public function customerService()
    {
        return $this->belongsTo(CustomerService::class);
    }
}
