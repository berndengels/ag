<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

	public function state()
	{
		return $this->belongsTo(State::class);
	}

	public function locations()
	{
		return $this->hasMany(Location::class);
	}

	public function __toString(): string
	{
		return $this->name;
	}
}
