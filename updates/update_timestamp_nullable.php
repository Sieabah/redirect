<?php

namespace ChrisS\Redirect\Updates;

use DbDongle;
use October\Rain\Database\Updates\Migration;

/**
 * Class UpdateTimestampsNullable
 *
 * @package ChrisS\Redirect\Updates
 */
class UpdateTimestampsNullable extends Migration
{

    public function up()
    {
        DbDongle::disableStrictMode();
        DbDongle::convertTimestamps('chriss_redirect_redirects', ['created_at', 'updated_at']);
    }

    public function down()
    {
        // ...
    }
}
