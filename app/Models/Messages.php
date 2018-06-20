<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'messages';

    public $timestamps = false;

    public function member()
    {
        return $this->belongsTo(Members::class, 'member_id');
    }

    public function students()
    {
        return $this->belongsTo(Students::class, 'member_id');
    }
}


