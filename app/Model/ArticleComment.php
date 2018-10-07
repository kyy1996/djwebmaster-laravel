<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ArticleComment
 *
 * @property int $id
 * @property int|null $uid 发表人用户UID
 * @property int $article_id 文章ID
 * @property int|null $parent_id 父评论ID，也就是被回复的评论ID
 * @property string $nickname 发表用户昵称
 * @property string $email 发表人邮箱
 * @property string $content 内容
 * @property string $ip 发表人IP
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at 软删除时间
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleComment whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleComment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleComment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleComment whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleComment whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleComment whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleComment whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleComment whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleComment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ArticleComment extends Model
{
    //
}
