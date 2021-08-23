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
        'op_sum_biaya',
        'op_tanggal_order',
        'op_status_order',
        'op_contact_customer',
        'op_note_to_customer',
        'op_bukti_transfer_filename',
        'op_bukti_transfer_file',
        'op_resi_filename',
        'op_resi_file'
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
        return $this->hasOneThrough( // Through Junction Table
            Product::class,
            OrderDetailProduct::class,
            'odp_op_id', // Foreign key on OrderDetailProduct that related to this
            'pdct_id', // Local key on Product that related to OrderDetailProduct
            'op_id', // Local key on this that related to OrderDetailProduct
            'odp_pdct_id' // Foreign key on OrderDetailProduct that related to Product
        );
    }
}
