<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * disk_metrics 表 - 磁盘容量指标
 *
 * 存储探针采集的磁盘空间使用情况
 * 支持多个挂载点，用于监控磁盘容量告警
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('disk_metrics', function (Blueprint $table) {
            // === 主键 ===
            $table->id(); // 自增主键
            $table->string('agent_id')->index(); // 探针 ID，关联 agents 表

            // === 磁盘指标 ===
            $table->string('mount_point'); // 挂载点路径（如 /, /home, /data）
            $table->bigInteger('total'); // 总容量（字节）
            $table->bigInteger('used'); // 已使用容量（字节）
            $table->bigInteger('free'); // 空闲容量（字节）
            $table->float('usage_percent'); // 磁盘使用率（百分比，0-100）

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
        Schema::dropIfExists('disk_metrics');
    }
};
