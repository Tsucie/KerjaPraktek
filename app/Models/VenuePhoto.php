<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @author Rizky A
 */
class VenuePhoto extends Model
{
    use HasFactory;

    protected $primaryKey = 'vp_id';
    protected $foreign = 'vp_vnu_id';
    
    protected $fillable = [
        'vp_vnu_id',
        'vp_filename',
        'vp_photo'
    ];
}
