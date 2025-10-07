<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AgencyLink
 *
 * @property int $id
 * @property string $model
 * @property string|null $link
 * @method static Builder|AgencyLink newModelQuery()
 * @method static Builder|AgencyLink newQuery()
 * @method static Builder|AgencyLink query()
 * @method static Builder|AgencyLink whereId($value)
 * @method static Builder|AgencyLink whereLink($value)
 * @method static Builder|AgencyLink whereModel($value)
 * @mixin Eloquent
 */
class AgencyLink extends Model
{
	protected $table = 'agency_links';
    protected $guarded = ['id'];
    public $timestamps = false;
}
