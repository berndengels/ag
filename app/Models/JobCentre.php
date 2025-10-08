<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\JobCentre
 *
 * @property int $id
 * @property string $name
 * @property string|null $url
 * @property string $street
 * @property int $postcode
 * @property string $city
 * @property string|null $post_address
 * @property string|null $email
 * @property string|null $fon
 * @property string|null $opening_time
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, CustomerAgency> $postcodes
 * @property-read int|null $postcodes_count
 * @method static Builder|JobCentre newModelQuery()
 * @method static Builder|JobCentre newQuery()
 * @method static Builder|JobCentre query()
 * @method static Builder|JobCentre whereCity($value)
 * @method static Builder|JobCentre whereCreatedAt($value)
 * @method static Builder|JobCentre whereEmail($value)
 * @method static Builder|JobCentre whereFon($value)
 * @method static Builder|JobCentre whereId($value)
 * @method static Builder|JobCentre whereName($value)
 * @method static Builder|JobCentre whereOpeningTime($value)
 * @method static Builder|JobCentre wherePostAddress($value)
 * @method static Builder|JobCentre wherePostcode($value)
 * @method static Builder|JobCentre whereStreet($value)
 * @method static Builder|JobCentre whereUpdatedAt($value)
 * @method static Builder|JobCentre whereUrl($value)
 * @mixin Eloquent
 */
class JobCentre extends Model
{
	protected $table = 'job_centres';
    protected $guarded = ['id'];

	public function postcodes(): MorphMany
	{
		return $this->morphMany(CustomerAgency::class, 'agency');
	}
}
