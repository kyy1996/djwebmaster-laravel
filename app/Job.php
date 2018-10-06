<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Job
 *
 * @property int $id
 * @property string $name 职位名称
 * @property string $department 部门
 * @property string $description 职位描述
 * @property int|null $article_id 关联文章ID
 * @property int $status 职位状态
 * @property int|null $uid 发布人UID
 * @property string|null $ip 发布人IP
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at 软删除时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Job whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Job extends Model
{
    //
}
