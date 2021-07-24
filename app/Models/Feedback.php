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
        'fb_id',
        'fb_ov_id',
        'fb_op_id',
        'fb_order_status',
        'fb_text',
        'fb_rating'
    ];

    protected $casts = [
        'fb_rating' => 'decimal'
    ];

    public function orderVenue()
    {
        return $this->belongsTo(OrderVenue::class, 'fb_ov_id', 'ov_id');
    }

    public function orderProduct()
    {
        return $this->belongsTo(OrderProduct::class, 'fb_op_id', 'op_id');
    }
}
