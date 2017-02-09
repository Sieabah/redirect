<?php

namespace ChrisS\Redirect\Updates;

use Illuminate\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

/**
 * Class AddCategoryIdToRedirectsTable
 *
 * @package ChrisS\Redirect\Updates
 */
class AddCategoryIdToRedirectsTable extends Migration
{
    public function up()
    {
        Schema::table('chriss_redirect_redirects', function (Blueprint $table) {
            $table->unsignedInteger('category_id')->after('id')->nullable();

            $table->foreign('category_id')
                ->references('id')
                ->on('chriss_redirect_categories')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('chriss_redirect_redirects', function (Blueprint $table) {
            $table->dropForeign('chriss_redirect_redirects_category_id_foreign');
            $table->dropColumn('category_id');
        });
    }
}
