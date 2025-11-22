<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * alert_records 表 - 告警触发记录
 *
 * 记录所有告警的触发和恢复历史
 * 用于告警通知和历史追溯
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alert_records', function (Blueprint $table) {
            // === 主键 ===
            $table->id(); // 自增主键

            // === 关联信息 ===
            $table->string('agent_id')->index(); // 探针 ID
            $table->string('config_id')->index(); // 告警配置 ID
            $table->string('config_name'); // 告警配置名称（冗余存储，便于查看）

            // === 告警内容 ===
            $table->string('alert_type'); // 告警类型（cpu/memory/disk/network）
            $table->text('message'); // 告警消息内容

            // === 阈值信息 ===
            $table->float('threshold')->nullable(); // 配置的阈值
            $table->float('actual_value')->nullable(); // 实际触发时的值

            // === 告警级别和状态 ===
            $table->string('level')->default('warning'); // 告警级别（info/warning/critical）
            $table->string('status')->default('firing'); // 告警状态（firing=告警中, resolved=已恢复）

            // === 时间信息 ===
            $table->bigInteger('fired_at')->index(); // 告警触发时间（毫秒时间戳）
            $table->bigInteger('resolved_at')->nullable(); // 告警恢复时间（毫秒时间戳），NULL 表示未恢复
            $table->bigInteger('created_at'); // 记录创建时间（毫秒时间戳）
            $table->bigInteger('updated_at'); // 记录更新时间（毫秒时间戳）

            // === 外键约束 ===
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade'); // 探针删除时级联删除告警记录
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_records');
    }
};
