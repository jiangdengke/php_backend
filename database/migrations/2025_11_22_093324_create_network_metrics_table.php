<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * network_metrics 表 - 网络流量指标
 *
 * 存储探针采集的网络接口流量数据
 * 支持多网卡，用于监控网络带宽使用情况
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('network_metrics', function (Blueprint $table) {
            // === 主键 ===
            $table->id(); // 自增主键
            $table->string('agent_id')->index(); // 探针 ID，关联 agents 表

            // === 网卡信息 ===
            $table->string('interface'); // 网卡接口名称（如 eth0, ens33, wlan0）

            // === 流量速率 ===
            $table->bigInteger('bytes_sent_rate')->nullable(); // 发送速率（字节/秒）
            $table->bigInteger('bytes_recv_rate')->nullable(); // 接收速率（字节/秒）

            // === 累计流量 ===
            $table->bigInteger('bytes_sent_total')->nullable(); // 累计发送字节数
            $table->bigInteger('bytes_recv_total')->nullable(); // 累计接收字节数

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
        Schema::dropIfExists('network_metrics');
    }
};
