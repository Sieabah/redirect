<?php

namespace ChrisS\Redirect\Models;

use Backend\Models\ExportModel;

/** @noinspection LongInheritanceChainInspection */

/**
 * Class RedirectExport
 *
 * @package ChrisS\Redirect\Models
 */
class RedirectExport extends ExportModel
{
    /**
     * {@inheritdoc}
     */
    public $table = 'chriss_redirect_redirects';

    /**
     * {@inheritdoc}
     */
    public function exportData($columns, $sessionKey = null)
    {
        return self::make()->get()->toArray();
    }
}
