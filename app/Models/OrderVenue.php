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
        'ov_id',
        'ov_cst_id',
        'ov_gst_id',
        'ov_vnu_id',
        'ov_vnu_nama',
        'ov_harga_sewa',
        'ov_nama_catering',
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
        'ov_status_order', // 0: Dalam Proses; 1: Terverifikasi; 2: Sudah Down Payment; 3: Selesai(Lunas); 4: Ditolak; 
        'ov_contact_customer',
        'ov_bukti_transfer_filename',
        'ov_bukti_transfer_file'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'ov_cst_id', 'cst_id');
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'ov_gst_id', 'gst_id');
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'ov_vnu_id', 'vnu_id');
    }
}
