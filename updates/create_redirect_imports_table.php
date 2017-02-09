<?php

namespace ChrisS\Redirect\Updates;

use Illuminate\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

/**
 * Class CreateRedirectImportsTable
 *
 * @package ChrisS\Redirect\Updates
 */
class CreateRedirectImportsTable extends Migration
{
    public function up()
    {
        Schema::create('chriss_redirect_redirect_imports', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chriss_redirect_redirect_imports');
    }
}
