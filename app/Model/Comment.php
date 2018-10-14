<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Comment
 *
 * @property int $id
 * @property string|null $commentable_type
 * @property int|null $commentable_id
 * @property int|null $uid 发表人用户UID
 * @property int|null $parent_id 父评论ID，也就是被回复的评论ID
 * @property string $nickname 发表用户昵称
 * @property string $email 发表人邮箱
 * @property string $content 内容
 * @property string $extra 额外信息：附件ID、相关URL
 * @property string $ip 发表人IP
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at 软删除时间
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommentableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereExtra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Comment extends Model
{
    //
}
