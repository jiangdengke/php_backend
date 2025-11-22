<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * monitor_metrics 表 - 服务监控检测结果
 *
 * 存储探针执行服务监控任务的详细检测结果
 * 支持 HTTP/TCP 等协议的可用性监控
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('monitor_metrics', function (Blueprint $table) {
            // === 主键 ===
            $table->id(); // 自增主键
            $table->string('agent_id')->index(); // 探针 ID，关联 agents 表

            // === 监控任务信息 ===
            $table->string('monitor_id')->index(); // 监控任务 ID，关联 monitor_tasks 表
            $table->string('type'); // 监控类型（http/tcp）
            $table->string('target', 500); // 监控目标地址（URL 或 IP:Port）

            // === 检测结果 ===
            $table->string('status'); // 状态（up=正常, down=异常）
            $table->integer('status_code')->nullable(); // HTTP 状态码（仅 HTTP 监控）
            $table->bigInteger('response_time')->nullable(); // 响应时间（毫秒）
            $table->text('error')->nullable(); // 错误信息（如连接超时、DNS 解析失败）
            $table->text('message')->nullable(); // 附加信息

            // === HTTP 特定检测 ===
            $table->boolean('content_match')->nullable(); // 内容匹配结果（是否包含预期关键词）

            // === SSL 证书检测 ===
            $table->bigInteger('cert_expiry_time')->nullable(); // 证书过期时间（毫秒时间戳），0 表示无证书
            $table->integer('cert_days_left')->nullable(); // 证书剩余天数

            // === 时间戳 ===
            $table->bigInteger('timestamp'); // 检测时间（毫秒时间戳）
            $table->timestamp('created_at')->nullable(); // 数据入库时间（Laravel 时间戳）

            // === 索引 ===
            $table->index(['agent_id', 'timestamp']); // 复合索引，用于按探针和时间查询
            $table->index('timestamp'); // 时间索引，用于时序查询
            $table->index('status'); // 状态索引，用于快速查询异常

            // === 外键约束 ===
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade'); // 探针删除时级联删除检测结果
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitor_metrics');
    }
};
