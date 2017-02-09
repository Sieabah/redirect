<?php

namespace ChrisS\Redirect\Models;

use Eloquent;
use October\Rain\Database\Model;

/** @noinspection ClassOverridesFieldOfSuperClassInspection */

/**
 * Class Client
 *
 * @package ChrisS\Redirect\Models
 * @mixin Eloquent
 */
class Client extends Model
{
    /**
     * {@inheritdoc}
     */
    public $table = 'chriss_redirect_clients';

    /**
     * {@inheritdoc}
     */
    protected $guarded = [];

    /**
     * {@inheritdoc}
     */
    public $belongsTo = [
        'redirect' => Redirect::class,
    ];

    /**
     * {@inheritdoc}
     */
    public $timestamps = false;
}
