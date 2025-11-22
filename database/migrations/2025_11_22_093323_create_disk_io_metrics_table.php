<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * disk_io_metrics 表 - 磁盘 IO 性能指标
 *
 * 存储探针采集的磁盘读写性能数据
 * 用于监控磁盘 IO 瓶颈和性能问题
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('disk_io_metrics', function (Blueprint $table) {
            // === 主键 ===
            $table->id(); // 自增主键
            $table->string('agent_id')->index(); // 探针 ID，关联 agents 表

            // === 设备信息 ===
            $table->string('device'); // 设备名称（如 sda, nvme0n1）

            // === IO 操作计数 ===
            $table->bigInteger('read_count')->nullable(); // 读取操作次数
            $table->bigInteger('write_count')->nullable(); // 写入操作次数

            // === IO 数据量 ===
            $table->bigInteger('read_bytes')->nullable(); // 累计读取字节数
            $table->bigInteger('write_bytes')->nullable(); // 累计写入字节数
            $table->bigInteger('read_bytes_rate')->nullable(); // 读取速率（字节/秒）
            $table->bigInteger('write_bytes_rate')->nullable(); // 写入速率（字节/秒）

            // === IO 时间统计 ===
            $table->bigInteger('read_time')->nullable(); // 读取总耗时（毫秒）
            $table->bigInteger('write_time')->nullable(); // 写入总耗时（毫秒）
            $table->bigInteger('io_time')->nullable(); // IO 总耗时（毫秒）
            $table->bigInteger('iops_in_progress')->nullable(); // 正在进行的 IO 操作数

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
        Schema::dropIfExists('disk_io_metrics');
    }
};
