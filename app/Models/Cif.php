<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cif extends Model
{
    use HasFactory;

    protected $table = 'cifs';

    protected $fillable = [
        'MOI',
        'GOI',
        'OCI',
        'NMH',
    ];
}
