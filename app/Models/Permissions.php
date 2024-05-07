<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permissions extends Model
{
    public static function getPermission($permission)
    {
        return self::getPermissions()[$permission] ?? '';
    }

    public static function getPermissions()
    {
        return [
            'roles_manage'       => __('roles.permissions.roles_manage'),
            'settings_manage'    => __('roles.permissions.settings_manage'),
            'subjects_manage'    => __('roles.permissions.subjects_manage'),
            'classrooms_manage'  => __('roles.permissions.classrooms_manage'),
            'classes_manage'     => __('roles.permissions.classes_manage'),
            'teachers_manage'    => __('roles.permissions.teachers_manage'),
            'students_manage'    => __('roles.permissions.students_manage'),
            'users_manage'       => __('roles.permissions.users_manage'),
            'courses_manage'     => __('roles.permissions.courses_manage'),
            'sections_manage'    => __('roles.permissions.sections_manage'),
            'lessons_manage'     => __('roles.permissions.lessons_manage'),
            'questions_manage'   => __('roles.permissions.questions_manage'),
            'assignments_manage' => __('roles.permissions.assignments_manage'),
            'quizzes_manage'     => __('roles.permissions.quizzes_manage'),
            'competitions_manage'=> __('roles.permissions.competitions_manage'),
            'wallet_codes_manage'=> __('roles.permissions.wallet_codes_manage'),
            'notice_manage'      => __('roles.permissions.notice_manage'),
            'reports_manage'     => __('roles.permissions.reports_manage'),
            'accounting_manage'  => __('roles.permissions.accounting_manage'),
            'cashback_manage'    => __('roles.permissions.cashback_manage'),
            'code_details_view'  => __('roles.permissions.code_details_view'),
            'posts_manage'       => __('roles.permissions.posts_manage'),
            'user_questions_manage'       => __('roles.permissions.user_questions_manage'),
            'sliders_manage'       => __('roles.permissions.sliders_manage'),
        ];
    }
}
