<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_document_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->boolean('syll')->default(false); // Sơ yếu lý lịch
            $table->boolean('cmt')->default(false); // Căn cước công dân/Chứng minh thư
            $table->boolean('sk')->default(false); // Giấy khám sức khỏe
            $table->boolean('gks')->default(false); // Giấy khai sinh
            $table->boolean('shk')->default(false); // Sổ hộ khẩu
            $table->boolean('dxv')->default(false); // Đơn xin việc
            $table->boolean('bc')->default(false); // Bằng cấp
            $table->boolean('gxnds')->default(false); // Giấy xác nhận dân sự
            $table->boolean('tk')->default(false); // Tờ khai
            $table->boolean('gtk')->default(false); // Giấy tờ khác
            $table->boolean('ckhn')->default(false); // Cam kết hội nhập
            $table->boolean('hdtv')->default(false); // Hợp đồng thử việc
            $table->boolean('hdld')->default(false); // Hợp đồng lao động
            $table->boolean('ttbm')->default(false); // Thỏa thuận bảo mật
            $table->boolean('ttthtu')->default(false); // Thỏa thuận thu hồi tạm ứng
            $table->boolean('dknpt')->default(false); // Đăng ký người phụ thuộc
            $table->boolean('ckt')->default(false); // Cam kết thuế
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_document_reports');
    }
};
