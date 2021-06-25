<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @author Rizky A
 */
class Guest extends Model
{
    use HasFactory;

    protected $primaryKey = 'gst_id';

    protected $fillable = [
        'gst_nama',
        'gst_alamat',
        'gst_no_telp',
        'gst_rencana_pemakaian',
        'gst_waktu_pemakaian',
        'gst_keperluan_pemakaian'
    ];
}
