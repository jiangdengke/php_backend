<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;



class Role extends Model
{
    // 对应roles表
    protected $table = 'roles';
    // 可批量填充字段
    protected $fillable = ['code','name'];
    // 关掉created_at和updated_at自动维护
    public $timestamps = false;

    //一个角色属于多个用户（多对对）
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
    //一个角色拥有多个权限（多对多）
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }
}
