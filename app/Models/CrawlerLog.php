<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CrawlerLog
 *
 * @property int $id
 * @property string|null $url
 * @property string|null $content
 * @method static Builder|CrawlerLog newModelQuery()
 * @method static Builder|CrawlerLog newQuery()
 * @method static Builder|CrawlerLog query()
 * @method static Builder|CrawlerLog whereContent($value)
 * @method static Builder|CrawlerLog whereId($value)
 * @method static Builder|CrawlerLog whereUrl($value)
 * @mixin Eloquent
 */
class CrawlerLog extends Model
{
    use HasFactory;

	protected $table = 'crawler_logs';
    protected $guarded = ['id'];
    public $timestamps = false;
}
