<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ZipCoordinate
 *
 * @property int $id
 * @property int $loc_id
 * @property string $zipcode
 * @property string $name
 * @property string $lat
 * @property string $lon
 * @property-read Jobcenter|null $jobcenter
 * @method static Builder|ZipCoordinate hasJobcenter()
 * @method static Builder|ZipCoordinate hasNoJobcenter()
 * @method static Builder|ZipCoordinate newModelQuery()
 * @method static Builder|ZipCoordinate newQuery()
 * @method static Builder|ZipCoordinate query()
 * @method static Builder|ZipCoordinate whereId($value)
 * @method static Builder|ZipCoordinate whereLat($value)
 * @method static Builder|ZipCoordinate whereLocId($value)
 * @method static Builder|ZipCoordinate whereLon($value)
 * @method static Builder|ZipCoordinate whereName($value)
 * @method static Builder|ZipCoordinate whereZipcode($value)
 * @property-read Arbeitsagentur|null $arbeitsagentur
 * @method static Builder|ZipCoordinate hasArbeitsagentur()
 * @method static Builder|ZipCoordinate hasNoArbeitsagentur()
 * @property int|null $community_id
 * @property string|null $extra
 * @property string $state
 * @property string|null $community
 * @property string|null $community_code
 * @property string|null $latitude
 * @property string|null $longitude
 * @property int|null $found_jc
 * @property int $found_aa
 * @method static Builder|ZipCoordinate whereCommunity($value)
 * @method static Builder|ZipCoordinate whereCommunityCode($value)
 * @method static Builder|ZipCoordinate whereCommunityId($value)
 * @method static Builder|ZipCoordinate whereExtra($value)
 * @method static Builder|ZipCoordinate whereFoundAa($value)
 * @method static Builder|ZipCoordinate whereFoundJc($value)
 * @method static Builder|ZipCoordinate whereLatitude($value)
 * @method static Builder|ZipCoordinate whereLongitude($value)
 * @method static Builder|ZipCoordinate whereState($value)
 * @mixin Eloquent
 */
class ZipCoordinate extends Model
{
    use HasFactory;

    protected $table = 'locations';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function scopeHasNoJobcenter(Builder $builder)
    {
        return $builder->doesntHave('jobcenter');
    }

    public function scopeHasJobcenter(Builder $builder)
    {
        return $builder->has('jobcenter');
    }

    public function scopeHasNoArbeitsagentur(Builder $builder)
    {
        return $builder->doesntHave('arbeitsagentur');
    }

    public function scopeHasArbeitsagentur(Builder $builder)
    {
        return $builder->has('arbeitsagentur');
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
