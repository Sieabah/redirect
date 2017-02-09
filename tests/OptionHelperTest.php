<?php

namespace ChrisS\Redirect\Tests;

use ChrisS\Redirect\Classes\OptionHelper;
use ChrisS\Redirect\Models\Redirect;
use PluginTestCase;

/**
 * Class OptionHelperTest
 *
 * @package ChrisS\Redirect\Tests
 */
class OptionHelperTest extends PluginTestCase
{
    public function testTargetTypeOptions()
    {
        self::assertNotCount(0, OptionHelper::getTargetTypeOptions());
        self::assertArrayHasKey(Redirect::TARGET_TYPE_PATH_URL, OptionHelper::getTargetTypeOptions());
        self::assertArrayHasKey(Redirect::TARGET_TYPE_CMS_PAGE, OptionHelper::getTargetTypeOptions());
    }
}
