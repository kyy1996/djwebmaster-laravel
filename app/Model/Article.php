<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Article
 *
 * @property int $id
 * @property int $uid 发表用户UID
 * @property string $title 文章标题
 * @property string|null $content 文章内容
 * @property string $cover_img 封面图片URL
 * @property string $tags 文章标签JSON数组
 * @property int $hide 是否隐藏
 * @property int $read_count 阅读次数
 * @property int $comment_count 评论数量
 * @property string $extra 文章额外信息JSON对象，可以扩展附件，活动ID
 * @property string|null $ip 发表人IP
 * @property string|null $deleted_at 软删除时间
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCommentCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCoverImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereExtra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereHide($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereReadCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Article extends Model
{
    //
}
