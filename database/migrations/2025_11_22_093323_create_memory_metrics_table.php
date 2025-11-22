<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * memory_metrics 表 - 内存使用指标
 *
 * 存储探针采集的内存和交换空间使用情况
 * 用于监控服务器的内存负载
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('memory_metrics', function (Blueprint $table) {
            // === 主键 ===
            $table->id(); // 自增主键
            $table->string('agent_id')->index(); // 探针 ID，关联 agents 表

            // === 内存指标 ===
            $table->bigInteger('total'); // 总内存容量（字节）
            $table->bigInteger('used'); // 已使用内存（字节）
            $table->bigInteger('free'); // 空闲内存（字节）
            $table->float('usage_percent'); // 内存使用率（百分比，0-100）

            // === 交换空间指标 ===
            $table->bigInteger('swap_total')->nullable(); // 总交换空间（字节）
            $table->bigInteger('swap_used')->nullable(); // 已使用交换空间（字节）
            $table->bigInteger('swap_free')->nullable(); // 空闲交换空间（字节）

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
        Schema::dropIfExists('memory_metrics');
    }
};
