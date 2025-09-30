<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Community
 *
 * @property int $id
 * @property int $state_id
 * @property string $name
 * @property string $code
 * @property-read Collection<int, Location> $locations
 * @property-read int|null $locations_count
 * @property-read State|null $state
 * @method static Builder|Community newModelQuery()
 * @method static Builder|Community newQuery()
 * @method static Builder|Community query()
 * @method static Builder|Community whereCode($value)
 * @method static Builder|Community whereId($value)
 * @method static Builder|Community whereName($value)
 * @method static Builder|Community whereStateId($value)
 * @mixin Eloquent
 */
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
