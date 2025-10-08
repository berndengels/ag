<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\EmploymentAgency
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
 * @property string|null $customer_postcode
 * @property string|null $customer_location
 * @property string|null $response
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, CustomerAgency> $postcodes
 * @property-read int|null $postcodes_count
 * @method static Builder|EmploymentAgency newModelQuery()
 * @method static Builder|EmploymentAgency newQuery()
 * @method static Builder|EmploymentAgency query()
 * @method static Builder|EmploymentAgency whereCity($value)
 * @method static Builder|EmploymentAgency whereCreatedAt($value)
 * @method static Builder|EmploymentAgency whereCustomerLocation($value)
 * @method static Builder|EmploymentAgency whereCustomerPostcode($value)
 * @method static Builder|EmploymentAgency whereEmail($value)
 * @method static Builder|EmploymentAgency whereFon($value)
 * @method static Builder|EmploymentAgency whereId($value)
 * @method static Builder|EmploymentAgency whereName($value)
 * @method static Builder|EmploymentAgency whereOpeningTime($value)
 * @method static Builder|EmploymentAgency wherePostAddress($value)
 * @method static Builder|EmploymentAgency wherePostcode($value)
 * @method static Builder|EmploymentAgency whereResponse($value)
 * @method static Builder|EmploymentAgency whereStreet($value)
 * @method static Builder|EmploymentAgency whereUpdatedAt($value)
 * @method static Builder|EmploymentAgency whereUrl($value)
 * @mixin Eloquent
 */
class EmploymentAgency extends Model
{
    protected $table = 'employment_agencies';
    protected $guarded = ['id'];

	public function postcodes(): MorphMany
	{
		return $this->morphMany(CustomerAgency::class, 'agency');
	}
}
