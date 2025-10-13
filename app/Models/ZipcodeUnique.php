<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Zipcode
 *
 * @property int $id
 * @property string|null $zipcode
 * @property int $found_jc
 * @property int $found_aa
 * @method static Builder|Zipcode newModelQuery()
 * @method static Builder|Zipcode newQuery()
 * @method static Builder|Zipcode query()
 * @method static Builder|Zipcode whereFoundAa($value)
 * @method static Builder|Zipcode whereFoundJc($value)
 * @method static Builder|Zipcode whereId($value)
 * @method static Builder|Zipcode whereZipcode($value)
 * @mixin Eloquent
 */
class ZipcodeUnique extends Model
{
	protected $table = 'zipcodes_unique';
    protected $guarded = ['id'];
    public $timestamps = false;

	protected $casts = [
		'found_jc'	=> 'boolean',
		'found_aa'	=> 'boolean',
		'error'	=> 'boolean',
	];
}
