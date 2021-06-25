<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @author Rizky A
 */
class Feedback extends Model
{
    use HasFactory;

    protected $primaryKey = 'fb_id';
    protected $foreign = ['fb_ov_id','fb_op_id'];

    protected $fillable = [
        'fb_ov_id',
        'fb_op_id',
        'fb_order_status',
        'fb_text'
    ];
}
