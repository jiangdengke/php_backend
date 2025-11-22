<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * host_metrics 表 - 主机系统信息
 *
 * 存储探针采集的主机操作系统和运行时信息
 * 用于了解服务器的系统配置和运行状态
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('host_metrics', function (Blueprint $table) {
            // === 主键 ===
            $table->id(); // 自增主键
            $table->string('agent_id')->index(); // 探针 ID，关联 agents 表

            // === 操作系统信息 ===
            $table->string('os')->nullable(); // 操作系统类型（linux/windows/darwin）
            $table->string('platform')->nullable(); // 平台名称（ubuntu/centos/debian/windows）
            $table->string('platform_version')->nullable(); // 平台版本号（如 22.04, 7.9）
            $table->string('kernel_version')->nullable(); // 内核版本号
            $table->string('kernel_arch')->nullable(); // 内核架构（x86_64/arm64）

            // === 运行状态 ===
            $table->bigInteger('uptime')->nullable(); // 系统运行时长（秒）
            $table->bigInteger('boot_time')->nullable(); // 系统启动时间（Unix 时间戳-秒）
            $table->bigInteger('procs')->nullable(); // 当前进程总数

            // === 时间戳 ===
            $table->bigInteger('timestamp'); // 数据采集时间（毫秒时间戳）
            $table->timestamp('created_at')->nullable(); // 数据入库时间（Laravel 时间戳）

            // === 索引 ===
            $table->index(['agent_id', 'timestamp']); // 复合索引，用于按探针和时间查询
            $table->index('timestamp'); // 时间索引，用于时序查询

            // === 外键约束 ===
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade'); // 探针删除时级联删除指标
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('host_metrics');
    }
};
