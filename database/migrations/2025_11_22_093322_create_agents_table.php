<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * agents 表 - 监控探针信息
 *
 * 存储所有监控探针的基本信息和在线状态
 * 探针是部署在各个服务器上的监控 Agent 程序
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agents', function (Blueprint $table) {
            // === 主键 ===
            $table->string('id')->primary(); // 探针唯一标识（UUID）

            // === 基本信息 ===
            $table->string('name')->index(); // 探针名称，用于识别不同服务器
            $table->string('hostname')->index()->nullable(); // 主机名
            $table->string('ip')->index()->nullable(); // IP 地址
            $table->string('os')->nullable(); // 操作系统（linux/windows/darwin）
            $table->string('arch')->nullable(); // 系统架构（amd64/arm64/386）
            $table->string('version')->nullable(); // 探针程序版本号
            $table->string('platform')->nullable(); // 平台详细信息（如 ubuntu, centos）
            $table->string('location')->nullable(); // 地理位置或机房标识

            // === 状态管理 ===
            $table->bigInteger('expire_time')->nullable(); // 服务到期时间（毫秒时间戳），用于授权管理
            $table->integer('status')->default(0); // 在线状态：0=离线, 1=在线
            $table->bigInteger('last_seen_at')->index(); // 最后心跳时间（毫秒时间戳），索引用于快速查询在线/离线状态

            // === 时间戳 ===
            $table->bigInteger('created_at'); // 创建时间（毫秒时间戳）
            $table->bigInteger('updated_at'); // 更新时间（毫秒时间戳）
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
