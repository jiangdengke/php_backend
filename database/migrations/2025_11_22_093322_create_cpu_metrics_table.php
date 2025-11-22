<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * cpu_metrics 表 - CPU 性能指标
 *
 * 存储探针采集的 CPU 使用率和配置信息
 * 用于监控服务器的 CPU 负载情况
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cpu_metrics', function (Blueprint $table) {
            // === 主键 ===
            $table->id(); // 自增主键
            $table->string('agent_id')->index(); // 探针 ID，关联 agents 表

            // === CPU 指标 ===
            $table->float('usage_percent'); // CPU 使用率（百分比，0-100）
            $table->integer('logical_cores')->nullable(); // 逻辑核心数（含超线程）
            $table->integer('physical_cores')->nullable(); // 物理核心数
            $table->string('model_name')->nullable(); // CPU 型号名称

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
        Schema::dropIfExists('cpu_metrics');
    }
};
