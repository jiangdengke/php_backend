<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * properties 表 - 系统配置属性
 *
 * 存储系统级别的通用配置信息
 * 用于动态配置管理（如通知渠道、邮件配置等）
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            // === 主键 ===
            $table->string('id')->primary(); // 配置项唯一标识（如 notification_channels, smtp_config）

            // === 配置信息 ===
            $table->string('name'); // 配置项可读名称
            $table->text('value'); // 配置项值（JSON 格式）

            // === 时间戳 ===
            $table->bigInteger('created_at'); // 创建时间（毫秒时间戳）
            $table->bigInteger('updated_at'); // 更新时间（毫秒时间戳）
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
