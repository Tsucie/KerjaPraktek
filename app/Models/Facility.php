<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @author Rizky A
 */
class Facility extends Model
{
    use HasFactory;

    protected $primaryKey = 'fc_id';

    protected $fillable = [
        'fc_nama',
        'fc_desc',
        'created_by',
        'updated_by'
    ];
}
