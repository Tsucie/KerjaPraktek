<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @author Rizky A
 */
class Promo extends Model
{
    use HasFactory;

    protected $primaryKey = 'prm_id';
    protected $foreign = ['prm_pdct_id','prm_vnu_id'];

    protected $fillable = [
        'prm_id',
        'prm_pdct_id',
        'prm_vnu_id',
        'prm_nama',
        'prm_disc_percent',
        'prm_harga_promo',
        'created_by',
        'updated_by'
    ];
}
