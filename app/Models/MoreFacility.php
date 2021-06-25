<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @author Rizky A
 */
class MoreFacility extends Model
{
    use HasFactory;

    protected $primaryKey = 'mfc_id';

    protected $fillable = [
        'mfc_nama',
        'mfc_satuan',
        'mfc_harga',
        'created_by',
        'updated_by'
    ];
}
