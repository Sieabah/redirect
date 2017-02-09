<?php

namespace ChrisS\Redirect\Updates;

use Illuminate\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

/**
 * Class AddSystemFieldToRedirectsTable
 *
 * @package ChrisS\Redirect\Updates
 */
class AddSystemFieldToRedirectsTable extends Migration
{
    const TABLE = 'chriss_redirect_redirects';

    public function up()
    {
        Schema::table(self::TABLE, function (Blueprint $table) {
            $table->boolean('system')->default(false)->after('publish_status');
        });
    }

    public function down()
    {
        Schema::table(self::TABLE, function (Blueprint $table) {
            $table->dropColumn('system');
        });
    }
}
