<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * monitor_tasks 表 - 服务监控任务配置
 *
 * 定义需要监控的服务和检测规则
 * 探针根据任务配置定期执行检测
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('monitor_tasks', function (Blueprint $table) {
            // === 主键 ===
            $table->string('id')->primary(); // 任务唯一标识（UUID）

            // === 基本信息 ===
            $table->string('name')->unique(); // 任务名称，唯一
            $table->string('type')->index(); // 监控类型（http/tcp/ping）
            $table->string('target', 500); // 监控目标地址（URL 或 IP:Port）
            $table->text('description')->nullable(); // 任务描述

            // === 任务配置 ===
            $table->boolean('enabled')->default(true); // 是否启用
            $table->boolean('show_target_public')->default(false); // 在公开状态页是否显示目标地址
            $table->integer('interval')->default(60); // 检测频率（秒）

            // === 探针分配 ===
            $table->json('agent_ids')->nullable(); // 指定的探针 ID 列表（JSON 数组），null 表示所有探针

            // === 协议特定配置 ===
            $table->json('http_config')->nullable(); // HTTP 监控配置（请求方法、Headers、超时等）
            $table->json('tcp_config')->nullable(); // TCP 监控配置（端口、超时等）

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
        Schema::dropIfExists('monitor_tasks');
    }
};
