<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * audit_results 表 - 系统审计结果
 *
 * 存储探针执行系统安全审计的结果
 * 用于安全扫描、合规检查等场景
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('audit_results', function (Blueprint $table) {
            // === 主键 ===
            $table->id(); // 自增主键

            // === 关联信息 ===
            $table->string('agent_id', 64)->index(); // 探针 ID，关联 agents 表

            // === 审计信息 ===
            $table->string('type', 32); // 审计类型（如 vps_audit, security_scan）
            $table->text('result'); // 审计结果详情（JSON 格式）

            // === 时间信息 ===
            $table->bigInteger('start_time'); // 审计开始时间（毫秒时间戳）
            $table->bigInteger('end_time'); // 审计结束时间（毫秒时间戳）
            $table->bigInteger('created_at'); // 记录创建时间（毫秒时间戳）

            // === 索引 ===
            $table->index('created_at'); // 创建时间索引，用于按时间查询

            // === 外键约束 ===
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade'); // 探针删除时级联删除审计结果
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_results');
    }
};
