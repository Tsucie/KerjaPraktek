<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @author Rizky A
 */
class OrderDetailProduct extends Model
{
    use HasFactory;

    protected $primaryKey = 'odp_id';
    protected $foreign = ['odp_op_id','odp_pdct_id'];

    protected $fillable = [
        'odp_id',
        'odp_op_id',
        'odp_pdct_id',
        'odp_pdct_kode',
        'odp_pdct_harga',
        'odp_pdct_qty'
    ];
}
