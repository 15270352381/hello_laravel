<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
	/**
	 * 一对多
     * 一个用户多条微博
	 */
	protected $fillable = ['content'];
    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
