<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Models\CustomerAgency
 *
 * @property int $id
 * @property string $agency_type
 * @property int $agency_id
 * @property int $postcode
 * @property-read Model|Eloquent $agency
 * @property-read mixed $data
 * @property-read mixed $name
 * @property-read mixed $pronomen
 * @property-read int|null $teilnehmer_count
 * @method static Builder|CustomerAgency newModelQuery()
 * @method static Builder|CustomerAgency newQuery()
 * @method static Builder|CustomerAgency query()
 * @method static Builder|CustomerAgency whereAgencyId($value)
 * @method static Builder|CustomerAgency whereAgencyType($value)
 * @method static Builder|CustomerAgency whereId($value)
 * @method static Builder|CustomerAgency wherePostcode($value)
 * @mixin Eloquent
 */
class CustomerAgency extends Model
{
    protected $table = 'customer_agencies';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function agency(): MorphTo
    {
        return $this->morphTo();
    }

	public function data(): Attribute
	{
		return Attribute::make(
			get: fn () => app($this->agency_type)->find($this->agency_id)
		);
	}

	public function pronomen(): Attribute
	{
		return Attribute::make(
			get: fn () => 'App\\Models\\JobCentre' === $this->agency_type ? 'das' : 'die'
		);
	}

	public function name(): Attribute
    {
        return Attribute::make(
            get: fn () => app($this->agency_type)->find($this->agency_id)->name ?? null
        );
    }
}
