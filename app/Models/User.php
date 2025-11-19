<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * 允许批量填充的字段
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * 不希望序列化给前端看到的字段
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    // 字段类型转换
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // 多对多：用户拥有多个角色
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
    // 判断是否拥有某个角色
    public function hasRole(string $code): bool
    {
        return $this->roles()->where('code', $code)->exists();
    }
    // 判断是否拥有某个权限
    public function hasPermission(string $code): bool
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($code) {
            $query->where('code', $code);
        })->exists();

}
