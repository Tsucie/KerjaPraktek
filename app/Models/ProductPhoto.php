<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @author Rizky A
 */
class ProductPhoto extends Model
{
    use HasFactory;

    protected $primaryKey = 'pp_id';
    protected $foreign = 'pp_pdct_id';

    protected $fillable = [
        'pp_pdct_id',
        'pp_filename',
        'pp_photo'
    ];
}
