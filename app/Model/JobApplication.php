<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\JobApplication
 *
 * @property int $id
 * @property int $job_id 申请的职位ID
 * @property int|null $uid 申请用户UID
 * @property string $stu_no 学号
 * @property string $school 学院
 * @property string $class 班级
 * @property string $name 姓名
 * @property string|null $resume 申请简历
 * @property string $extra 额外信息：附件ID
 * @property int $status 状态：-1-已拒绝/0-申请中/1-已审核/2-已通过
 * @property int|null $operator_uid 操作人UID
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at 软删除时间
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereExtra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereJobId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereOperatorUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereResume($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereSchool($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereStuNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobApplication whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class JobApplication extends Model
{
    //
}
