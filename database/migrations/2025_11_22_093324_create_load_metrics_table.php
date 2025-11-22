<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * load_metrics 表 - 系统负载指标
 *
 * 存储探针采集的系统平均负载数据
 * 用于监控系统整体压力和性能状况
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('load_metrics', function (Blueprint $table) {
            // === 主键 ===
            $table->id(); // 自增主键
            $table->string('agent_id')->index(); // 探针 ID，关联 agents 表

            // === 系统负载 ===
            $table->float('load1'); // 1 分钟平均负载
            $table->float('load5'); // 5 分钟平均负载
            $table->float('load15'); // 15 分钟平均负载

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
        Schema::dropIfExists('load_metrics');
    }
};
