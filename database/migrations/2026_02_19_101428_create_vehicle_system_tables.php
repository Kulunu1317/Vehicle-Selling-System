<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // 1. UPDATE the existing Users Table (Add our custom columns)
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->enum('role', ['admin', 'hr', 'owner'])->default('hr')->after('password');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('role');
        });

        // 2. CREATE Packages Table
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('max_ads');
            $table->integer('expiry_time');
            $table->enum('expiry_unit', ['minutes', 'hours', 'days']);
            $table->decimal('price', 10, 2);
            $table->enum('tier', ['normal', 'silver', 'gold', 'diamond']);
            $table->string('image')->nullable();
            $table->json('extra_questions')->nullable();
            $table->timestamps();
        });

        // 3. CREATE HR Purchased Packages Table
        Schema::create('hr_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->integer('ads_remaining');
            $table->timestamp('expires_at');
            $table->timestamps();
        });

        // 4. CREATE Advertisements Table
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('hr_package_id')->constrained('hr_packages')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->json('vehicle_data'); 
            $table->timestamps();
        });

        // 5. CREATE Owner Submissions Table
        Schema::create('owner_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertisement_id')->constrained('advertisements')->onDelete('cascade');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('owner_submissions');
        Schema::dropIfExists('advertisements');
        Schema::dropIfExists('hr_packages');
        Schema::dropIfExists('packages');
        
        // Remove the columns we added to users if we rollback
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'role', 'status']);
        });
    }
};