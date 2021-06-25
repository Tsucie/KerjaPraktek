<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @author Rizky A
 */
class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'pdct_id';

    protected $fillable = [
        'pdct_kode',
        'pdct_nama',
        'pdct_desc',
        'pdct_harga',
        'pdct_stock',
        'created_by',
        'updated_by'
    ];

    public function hasPhoto()
    {
        return $this->hasMany(ProductPhoto::class, 'ph_pdct_id', 'pdct_id');
    }

    public function hasInventory()
    {
        return $this->hasOne(Inventory::class, 'ivty_pdct_id', 'pdct_id');
    }
}
