<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\State
 *
 * @property int $id
 * @property string $name
 * @property-read Collection<int, Community> $communities
 * @property-read int|null $communities_count
 * @method static Builder|State newModelQuery()
 * @method static Builder|State newQuery()
 * @method static Builder|State query()
 * @method static Builder|State whereId($value)
 * @method static Builder|State whereName($value)
 * @mixin Eloquent
 */
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
