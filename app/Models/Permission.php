<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;



class Permission extends Model
{
    // 对应permissions表
    protected $table = 'permissions';
    // 可批量填充字段
    protected $fillable = ['code','name'];
    // 关掉created_at和updated_at自动维护
    public $timestamps = false;
    // 一个权限属于多个角色（多对多）
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
