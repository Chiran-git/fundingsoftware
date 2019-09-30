<?php

use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveIsSuperAdminAndAddUserTypeToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Find all super admins
        $superAdmins = User::where('is_super_admin', 1)->get();

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_super_admin');
            $table->string('user_type')->after('email')->default('organization');
        });

        // Set super admin's user_type as superadmin
        foreach ($superAdmins as $user) {
            $user->user_type = 'superadmin';
            $user->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Find all super admins
        $superAdmins = User::where('user_type', 'superadmin')->get();

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('user_type');
            $table->boolean('is_super_admin')->after('email')->default(0);
        });

        // Set super admin's is_super_admin as 1
        foreach ($superAdmins as $user) {
            $user->is_super_admin = 1;
            $user->save();
        }
    }
}
