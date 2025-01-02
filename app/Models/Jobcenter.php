<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Jobcenter
 *
 * @property int $id
 * @property string $name
 * @property string $street
 * @property string $postcode
 * @property string $city
 * @property string $email
 * @property string|null $fon
 * @property string|null $opening_time
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Jobcenter newModelQuery()
 * @method static Builder|Jobcenter newQuery()
 * @method static Builder|Jobcenter query()
 * @method static Builder|Jobcenter whereCity($value)
 * @method static Builder|Jobcenter whereCreatedAt($value)
 * @method static Builder|Jobcenter whereEmail($value)
 * @method static Builder|Jobcenter whereFon($value)
 * @method static Builder|Jobcenter whereId($value)
 * @method static Builder|Jobcenter whereName($value)
 * @method static Builder|Jobcenter whereOpeningTime($value)
 * @method static Builder|Jobcenter wherePostcode($value)
 * @method static Builder|Jobcenter whereStreet($value)
 * @method static Builder|Jobcenter whereUpdatedAt($value)
 * @property string $customer_postcode
 * @property string|null $response
 * @method static Builder|Jobcenter whereCustomerPostcode($value)
 * @method static Builder|Jobcenter whereResponse($value)
 * @property string|null $info
 * @property string|null $url
 * @method static Builder|Jobcenter whereInfo($value)
 * @method static Builder|Jobcenter whereUrl($value)
 * @property string $customer_location
 * @method static Builder|Jobcenter whereCustomerLocation($value)
 * @mixin Eloquent
 */
class Jobcenter extends Model
{
	protected $table = 'jobcenters';
    protected $guarded = ['id'];
	public $timestamps = false;
}
