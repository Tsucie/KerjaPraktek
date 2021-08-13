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
    protected $table = 'feedbacks';

    protected $fillable = [
        'fb_id',
        'fb_vnu_id',
        'fb_pdct_id',
        'fb_cst_nama',
        'fb_cst_email',
        'fb_text',
        'fb_rating'
    ];

    public function venue() {
        return $this->belongsTo(Venue::class, 'fb_vnu_id', 'vnu_id');
    }

    public function product() {
        return $this->belongsTo(Product::class, 'fb_pdct_id', 'pdct_id');
    }
}
