<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Zipcode
 *
 * @property int $id
 * @property string|null $loc_id
 * @property string|null $zipcode
 * @property string|null $name
 * @property float|null $lat
 * @property float|null $lon
 * @property int $found_jc
 * @property int $found_aa
 * @method static Builder|Zipcode newModelQuery()
 * @method static Builder|Zipcode newQuery()
 * @method static Builder|Zipcode query()
 * @method static Builder|Zipcode whereFoundAa($value)
 * @method static Builder|Zipcode whereFoundJc($value)
 * @method static Builder|Zipcode whereId($value)
 * @method static Builder|Zipcode whereLat($value)
 * @method static Builder|Zipcode whereLocId($value)
 * @method static Builder|Zipcode whereLon($value)
 * @method static Builder|Zipcode whereName($value)
 * @method static Builder|Zipcode whereZipcode($value)
 * @mixin Eloquent
 */
class Zipcode extends Model
{
	protected $table = 'zipcodes';
    protected $guarded = ['id'];
    public $timestamps = false;

	protected $casts = [
		'found_jc'	=> 'boolean',
		'found_aa'	=> 'boolean',
	];
}
