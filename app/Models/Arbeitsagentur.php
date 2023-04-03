<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Arbeitsagentur
 *
 * @property int $id
 * @property string $customer_postcode
 * @property string $name
 * @property string|null $info
 * @property string|null $url
 * @property string $street
 * @property string $postcode
 * @property string $city
 * @property string|null $email
 * @property string|null $fon
 * @property string|null $opening_time
 * @property string|null $response
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Arbeitsagentur newModelQuery()
 * @method static Builder|Arbeitsagentur newQuery()
 * @method static Builder|Arbeitsagentur query()
 * @method static Builder|Arbeitsagentur whereCity($value)
 * @method static Builder|Arbeitsagentur whereCreatedAt($value)
 * @method static Builder|Arbeitsagentur whereCustomerPostcode($value)
 * @method static Builder|Arbeitsagentur whereEmail($value)
 * @method static Builder|Arbeitsagentur whereFon($value)
 * @method static Builder|Arbeitsagentur whereId($value)
 * @method static Builder|Arbeitsagentur whereInfo($value)
 * @method static Builder|Arbeitsagentur whereName($value)
 * @method static Builder|Arbeitsagentur whereOpeningTime($value)
 * @method static Builder|Arbeitsagentur wherePostcode($value)
 * @method static Builder|Arbeitsagentur whereResponse($value)
 * @method static Builder|Arbeitsagentur whereStreet($value)
 * @method static Builder|Arbeitsagentur whereUpdatedAt($value)
 * @method static Builder|Arbeitsagentur whereUrl($value)
 * @property string $customer_location
 * @method static Builder|Arbeitsagentur whereCustomerLocation($value)
 * @mixin Eloquent
 */
class Arbeitsagentur extends Model
{
    use HasFactory;

    protected $table = 'arbeitsagenturen';
    protected $guarded = ['id'];
}
