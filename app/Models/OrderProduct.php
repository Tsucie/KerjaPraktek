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
        'op_id',
        'op_cst_id',
        'op_lokasi_pengiriman',
        'op_sum_harga_produk',
        'op_harga_ongkir',
        'op_persen_pajak',
        'op_nominal_pajak',
        'op_alamat_pengiriman',
        'op_alamat_pemesanan',
        'op_tanggal_order',
        'op_status_order',
        'op_contact_customer'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'op_cst_id', 'cst_id');
    }

    public function detail()
    {
        return $this->hasOne(OrderDetailProduct::class, 'odp_op_id', 'op_id');
    }

    public function product()
    {
        return $this->hasOneThrough(
            Product::class,
            OrderDetailProduct::class,
            'pdct_id',
            'odp_pdct_id',
            'op_id',
            'odp_op_id'
        );
    }
}
