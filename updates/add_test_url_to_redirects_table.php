<?php

namespace ChrisS\Redirect\Updates;

use Illuminate\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

/**
 * Class AddTestUrlToRedirectsTable
 *
 * @package ChrisS\Redirect\Updates
 */
class AddTestUrlToRedirectsTable extends Migration
{
    const TABLE = 'chriss_redirect_redirects';

    public function up()
    {
        Schema::table(self::TABLE, function (Blueprint $table) {
            $table->mediumText('test_url')->nullable()->after('to_url');
        });
    }

    public function down()
    {
        Schema::table(self::TABLE, function (Blueprint $table) {
            $table->dropColumn('test_url');
        });
    }
}
