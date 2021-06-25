<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @author Rizky A
 */
class OrderVenue extends Model
{
    use HasFactory;

    protected $primaryKey = 'ov_id';
    protected $foreign = ['ov_cst_id','ov_gst_id','ov_vnu_id'];

    protected $fillable = [
        'ov_cst_id',
        'ov_gst_id',
        'ov_vnu_id',
        'ov_vnu_nama',
        'ov_no_telp',
        'ov_biaya_lain',
        'ov_fee_catering',
        'ov_fee_pelaminan',
        'ov_more_facilities',
        'ov_lain_lain',
        'ov_sum_lain_lain',
        'ov_sum_biaya',
        'ov_down_payment',
        'ov_remaining_payment',
        'ov_status_order'
    ];
}
