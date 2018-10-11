<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\UserLog
 *
 * @property int $id
 * @property int $uid 用户UID
 * @property string $title 行为标题
 * @property string $description 行为描述，可以是markdown
 * @property string|null $loggable_type
 * @property int|null $loggable_id
 * @property int $result 行为执行结果：0-失败/1-成功
 * @property string $extra 额外信息，相关URL/相关附件ID/相关文章ID/相关活动、职位/执行结果/失败原因等
 * @property string|null $ip 操作人IP
 * @property string $ua 操作人User-Agent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at 软删除时间
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereExtra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereLoggableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereLoggableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereUa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserLog extends Model
{
    //
}
