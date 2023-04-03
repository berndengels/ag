<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Location
 *
 * @property int $id
 * @property int $zipcode
 * @property string $place
 * @property string $state
 * @property string|null $community
 * @property string|null $community_code
 * @property string|null $latitude
 * @property string|null $longitude
 * @method static Builder|Location newModelQuery()
 * @method static Builder|Location newQuery()
 * @method static Builder|Location query()
 * @method static Builder|Location whereCommunity($value)
 * @method static Builder|Location whereCommunityCode($value)
 * @method static Builder|Location whereId($value)
 * @method static Builder|Location whereLatitude($value)
 * @method static Builder|Location whereLongitude($value)
 * @method static Builder|Location wherePlace($value)
 * @method static Builder|Location whereState($value)
 * @method static Builder|Location whereZipcode($value)
 * @property-read Jobcenter|null $jobcenter
 * @method static Builder|Location hasJobcenter()
 * @method static Builder|Location hasNoJobcenter()
 * @property string $name
 * @property string|null $extra
 * @property-read Arbeitsagentur|null $arbeitsagentur
 * @method static Builder|Location hasArbeitsagentur()
 * @method static Builder|Location hasNoArbeitsagentur()
 * @method static Builder|Location whereExtra($value)
 * @method static Builder|Location whereName($value)
 * @mixin Eloquent
 */
class Location extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public $timestamps = false;

    public function scopeHasNoJobcenter(Builder $builder)
    {
        return $builder->doesntHave('jobcenters');
    }

    public function scopeHasJobcenter(Builder $builder)
    {
        return $builder->has('jobcenters');
    }

    public function scopeHasNoArbeitsagentur(Builder $builder)
    {
        return $builder->doesntHave('arbeitsagenturen');
    }

    public function scopeHasArbeitsagentur(Builder $builder)
    {
        return $builder->has('arbeitsagenturen');
    }

    public function jobcenter()
    {
        return $this->hasOne(Jobcenter::class, 'customer_postcode', 'zipcode');
    }

    public function arbeitsagentur()
    {
        return $this->hasOne(Arbeitsagentur::class, 'customer_postcode', 'zipcode');
    }
}
