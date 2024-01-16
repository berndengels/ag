<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

	public function communities()
	{
		return $this->hasMany(Community::class);
	}

	public function __toString(): string
	{
		return $this->name;
	}
}
