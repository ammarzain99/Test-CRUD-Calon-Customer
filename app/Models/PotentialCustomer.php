<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PotentialCustomer extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'potential_customers';

    protected $fillable = [
        'nama',
        'no_wa',
        'email',
        'nama_lembaga'
    ];
}
