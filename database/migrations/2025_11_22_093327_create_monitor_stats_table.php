<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * monitor_stats 表 - 服务监控统计数据
 *
 * 聚合每个监控任务的统计指标
 * 用于展示可用率、平均响应时间等汇总信息
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('monitor_stats', function (Blueprint $table) {
            // === 主键 ===
            $table->string('id')->primary(); // 统计记录唯一标识（UUID）

            // === 关联信息 ===
            $table->string('agent_id')->index(); // 探针 ID
            $table->string('monitor_id')->index(); // 监控任务 ID
            $table->string('monitor_type'); // 监控类型（http/tcp）
            $table->string('target', 500); // 监控目标地址

            // === 响应时间统计 ===
            $table->bigInteger('current_response')->nullable(); // 当前响应时间（毫秒）
            $table->bigInteger('avg_response_24h')->nullable(); // 24 小时平均响应时间（毫秒）

            // === 可用率统计 ===
            $table->float('uptime_24h')->nullable(); // 24 小时在线率（百分比，0-100）
            $table->float('uptime_30d')->nullable(); // 30 天在线率（百分比，0-100）

            // === SSL 证书信息 ===
            $table->bigInteger('cert_expiry_date')->nullable(); // 证书过期时间（毫秒时间戳），0 表示无证书
            $table->integer('cert_expiry_days')->nullable(); // 证书剩余天数

            // === 检测次数统计 ===
            $table->bigInteger('total_checks_24h')->nullable(); // 24 小时总检测次数
            $table->bigInteger('success_checks_24h')->nullable(); // 24 小时成功次数
            $table->bigInteger('total_checks_30d')->nullable(); // 30 天总检测次数
            $table->bigInteger('success_checks_30d')->nullable(); // 30 天成功次数

            // === 最新状态 ===
            $table->bigInteger('last_check_time')->nullable(); // 最后检测时间（毫秒时间戳）
            $table->string('last_check_status')->nullable(); // 最后检测状态（up/down）

            // === 时间戳 ===
            $table->bigInteger('updated_at'); // 更新时间（毫秒时间戳）

            // === 索引 ===
            $table->index(['agent_id', 'monitor_id']); // 复合索引，用于快速查询某探针的某任务统计

            // === 外键约束 ===
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade'); // 探针删除时级联删除统计数据
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitor_stats');
    }
};
