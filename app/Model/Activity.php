<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Activity
 *
 * @property int $id
 * @property string $name 活动名称
 * @property string $type 活动类型：教学活动/游戏/面基/xxx
 * @property string $location 活动举办地点
 * @property string $host 活动主持人：孔元元，或者孔元元/王一帅，斜杠分割多个人
 * @property string $time 活动举办时间：周三20:00
 * @property string $comment 活动备注
 * @property int $article_id 活动关联文章ID，可为空
 * @property string $host_uids 活动主持人对应UID数组：[1,2]，因为一个活动可以有多个人来主持
 * @property int $availability 活动可容纳人数
 * @property int $signup_amount 活动报名人数，程序自动更新
 * @property int $hide 是否属于隐藏活动
 * @property int $pause 是否暂停该活动
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at 软删除时间
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereAvailability($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereHide($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereHost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereHostUids($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity wherePause($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereSignupAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Activity extends Model
{
    //
}
