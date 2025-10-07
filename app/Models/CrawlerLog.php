<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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
 * @property string|null $link
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|CrawlerLog whereCreatedAt($value)
 * @method static Builder|CrawlerLog whereLink($value)
 * @method static Builder|CrawlerLog whereUpdatedAt($value)
 * @mixin Eloquent
 */
class CrawlerLog extends Model
{
    use HasFactory;

	protected $table = 'crawler_logs';
    protected $guarded = ['id'];
}
