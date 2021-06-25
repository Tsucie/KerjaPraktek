<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @author Rizky A
 */
class OrderProduct extends Model
{
    use HasFactory;

    protected $primaryKey = 'op_id';
    protected $foreign = 'op_cst_id';

    protected $fillable = [
        'op_cst_id',
        'op_lokasi_pengiriman',
        'op_sum_harga_produk',
        'op_harga_ongkir',
        'op_persen_pajak',
        'op_nominal_pajak',
        'op_alamat_pengiriman',
        'op_alamat_pemesanan',
        'op_tanggal_order',
        'op_status_order'
    ];
}
