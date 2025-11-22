<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * gpu_metrics 表 - GPU 性能指标
 *
 * 存储探针采集的 GPU 使用率和状态信息
 * 支持多 GPU，用于监控深度学习/挖矿服务器
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gpu_metrics', function (Blueprint $table) {
            // === 主键 ===
            $table->id(); // 自增主键
            $table->string('agent_id')->index(); // 探针 ID，关联 agents 表

            // === GPU 基本信息 ===
            $table->integer('index'); // GPU 索引编号（0, 1, 2...）
            $table->string('name')->nullable(); // GPU 型号名称（如 RTX 3090）

            // === GPU 使用率 ===
            $table->float('utilization')->nullable(); // GPU 核心使用率（百分比，0-100）

            // === 显存信息 ===
            $table->bigInteger('memory_total')->nullable(); // 总显存容量（字节）
            $table->bigInteger('memory_used')->nullable(); // 已使用显存（字节）
            $table->bigInteger('memory_free')->nullable(); // 空闲显存（字节）

            // === 温度和功耗 ===
            $table->float('temperature')->nullable(); // GPU 温度（摄氏度）
            $table->float('power_draw')->nullable(); // 当前功耗（瓦）
            $table->float('fan_speed')->nullable(); // 风扇转速（百分比，0-100）

            // === 性能状态 ===
            $table->string('performance_state')->nullable(); // 性能状态（P0-P12，P0 最高性能）

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
        Schema::dropIfExists('gpu_metrics');
    }
};
