<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    // 对应roles表
    protected $table = 'roles';
    // 可批量填充字段
}
