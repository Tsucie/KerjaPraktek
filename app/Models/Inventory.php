<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @author Rizky A
 */
class Inventory extends Model
{
    use HasFactory;

    protected $primaryKey = 'ivty_id';
    protected $foreign = 'ivty_pdct_id';

    protected $fillable = [
        'ivty_id',
        'ivty_pdct_id',
        'ivty_pdct_nama',
        'ivty_pdct_stock',
        'ivty_cause'
    ];
}
