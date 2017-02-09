<?php

namespace ChrisS\Redirect\Updates;

use Illuminate\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

/**
 * Class RemovePublishStatusColumnRedirectsTable
 *
 * @package ChrisS\Redirect\Updates
 */
class RemovePublishStatusColumnRedirectsTable extends Migration
{
    const TABLE = 'chriss_redirect_redirects';

    public function up()
    {
        Schema::table(self::TABLE, function (Blueprint $table) {
            $table->dropColumn('publish_status');
        });
    }

    public function down()
    {
        Schema::table(self::TABLE, function (Blueprint $table) {
            $table->boolean('publish_status')->default(false);
        });
    }
}
