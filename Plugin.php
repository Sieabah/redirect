<?php

namespace ChrisS\Redirect;

use ChrisS\Redirect\Classes\PageHandler;
use ChrisS\Redirect\Classes\PublishManager;
use ChrisS\Redirect\Classes\RedirectManager;
use ChrisS\Redirect\Classes\StaticPageHandler;
use ChrisS\Redirect\Models\Redirect;
use App;
use Backend;
use Cms\Classes\Page;
use Event;
use Request;
use System\Classes\PluginBase;

/**
 * Class Plugin
 *
 * @package ChrisS\Redirect
 */
class Plugin extends PluginBase
{
    /**
     * {@inheritdoc}
     */
    public function pluginDetails()
    {
        return [
            'name' => 'chriss.redirect::lang.plugin.name',
            'description' => 'chriss.redirect::lang.plugin.description',
            'author' => 'Alwin Drenth',
            'icon' => 'icon-link',
            'homepage' => 'https://github.com/Sieabah/redirect',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        if (App::runningInBackend()
            && !App::runningInConsole()
            && !App::runningUnitTests()
        ) {
            $this->bootBackend();
        }

        if (!App::runningInBackend()
            && !App::runningUnitTests()
            && !App::runningInConsole()
        ) {
            $this->bootFrontend();
        }
    }

    /**
     * Boot stuff for Frontend
     *
     * @return void
     */
    public function bootFrontend()
    {
        // Check for running in console or backend before route matching
        $rulesPath = storage_path('app/redirects.csv');

        if (!file_exists($rulesPath) || !is_readable($rulesPath)) {
            return;
        }

        $requestUri = str_replace(Request::getBasePath(), '', Request::getRequestUri());
        $manager = RedirectManager::createWithRulesPath($rulesPath);
        $rule = $manager->match($requestUri);

        if ($rule) {
            $manager->redirectWithRule($rule, $requestUri);
        }
    }

    /**
     * Boot stuff for Backend
     *
     * @return void
     */
    public function bootBackend()
    {
        Page::extend(function (Page $page) {
            $handler = new PageHandler($page);

            $page->bindEvent('model.beforeUpdate', function () use ($handler) {
                $handler->onBeforeUpdate();
            });

            $page->bindEvent('model.afterDelete', function () use ($handler) {
                $handler->onAfterDelete();
            });
        });

        if (class_exists('\RainLab\Pages\Classes\Page')) {
            \RainLab\Pages\Classes\Page::extend(function (\RainLab\Pages\Classes\Page $page) {
                $handler = new StaticPageHandler($page);

                $page->bindEvent('model.beforeUpdate', function () use ($handler) {
                    $handler->onBeforeUpdate();
                });

                $page->bindEvent('model.afterDelete', function () use ($handler) {
                    $handler->onAfterDelete();
                });
            });
        }

        Event::listen('redirects.changed', function () {
            PublishManager::instance()->publish();
        });
    }

    /**
     * {@inheritdoc}
     */
    public function registerPermissions()
    {
        return [
            'chriss.redirect.access_redirects' => [
                'label' => 'chriss.redirect::lang.permission.access_redirects.label',
                'tab' => 'chriss.redirect::lang.permission.access_redirects.tab',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function registerNavigation()
    {
        return [
            'redirect' => [
                'label' => 'chriss.redirect::lang.navigation.menu_label',
                'icon' => 'icon-link',
                'url' => Backend::url('chriss/redirect/statistics'),
                'order' => 50,
                'permissions' => [
                    'chriss.redirect.access_redirects',
                ],
                'sideMenu' => [
                    'statistics' => [
                        'icon' => 'icon-bar-chart',
                        'label' => 'chriss.redirect::lang.title.statistics',
                        'url' => Backend::url('chriss/redirect/statistics'),
                        'permissions' => [
                            'chriss.redirect.access_redirects',
                        ],
                    ],
                    'redirects' => [
                        'icon' => 'icon-link',
                        'label' => 'chriss.redirect::lang.navigation.menu_label',
                        'url' => Backend::url('chriss/redirect/redirects'),
                        'permissions' => [
                            'chriss.redirect.access_redirects',
                        ],
                    ],
                    'reorder' => [
                        'label' => 'chriss.redirect::lang.buttons.reorder_redirects',
                        'url' => Backend::url('chriss/redirect/redirects/reorder'),
                        'icon' => 'icon-sort-amount-asc',
                        'permissions' => [
                            'chriss.redirect.access_redirects',
                        ],
                    ],
                    'logs' => [
                        'label' => 'chriss.redirect::lang.buttons.logs',
                        'url' => Backend::url('chriss/redirect/logs'),
                        'icon' => 'icon-file-text-o',
                        'permissions' => [
                            'chriss.redirect.access_redirects',
                        ],
                    ],
                    'categories' => [
                        'label' => 'chriss.redirect::lang.buttons.categories',
                        'url' => Backend::url('chriss/redirect/categories'),
                        'icon' => 'icon-tag',
                        'permissions' => [
                            'chriss.redirect.access_redirects',
                        ],
                    ],
                    'import' => [
                        'label' => 'chriss.redirect::lang.buttons.import',
                        'url' => Backend::url('chriss/redirect/redirects/import'),
                        'icon' => 'icon-download',
                        'permissions' => [
                            'chriss.redirect.access_redirects',
                        ],
                    ],
                    'export' => [
                        'label' => 'chriss.redirect::lang.buttons.export',
                        'url' => Backend::url('chriss/redirect/redirects/export'),
                        'icon' => 'icon-upload',
                        'permissions' => [
                            'chriss.redirect.access_redirects',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function registerListColumnTypes()
    {
        return [
            'redirect_switch_color' => function ($value) {
                $format = '<div class="oc-icon-circle" style="color: %s">%s</div>';

                if ((int) $value === 1) {
                    return sprintf($format, '#95b753', e(trans('backend::lang.list.column_switch_true')));
                }

                return sprintf($format, '#cc3300', e(trans('backend::lang.list.column_switch_false')));
            },
            'redirect_match_type' => function ($value) {
                switch ($value) {
                    case Redirect::TYPE_EXACT:
                        return e(trans('chriss.redirect::lang.redirect.exact'));
                    case Redirect::TYPE_PLACEHOLDERS:
                        return e(trans('chriss.redirect::lang.redirect.placeholders'));
                    default:
                        return $value;
                }
            },
            'redirect_status_code' => function ($value) {
                switch ($value) {
                    case 301:
                        return e(trans('chriss.redirect::lang.redirect.permanent'));
                    case 302:
                        return e(trans('chriss.redirect::lang.redirect.temporary'));
                    case 303:
                        return e(trans('chriss.redirect::lang.redirect.see_other'));
                    case 404:
                        return e(trans('chriss.redirect::lang.redirect.not_found'));
                    case 410:
                        return e(trans('chriss.redirect::lang.redirect.gone'));
                    default:
                        return $value;
                }
            },
            'redirect_target_type' => function ($value) {
                switch ($value) {
                    case Redirect::TARGET_TYPE_PATH_URL:
                        return e(trans('chriss.redirect::lang.redirect.target_type_path_or_url'));
                    case Redirect::TARGET_TYPE_CMS_PAGE:
                        return e(trans('chriss.redirect::lang.redirect.target_type_cms_page'));
                    case Redirect::TARGET_TYPE_STATIC_PAGE:
                        return e(trans('chriss.redirect::lang.redirect.target_type_static_page'));
                    default:
                        return $value;
                }
            },
            'redirect_from_url' => function ($value) {
                $maxChars = 40;
                $textLength = strlen($value);
                if ($textLength > $maxChars) {
                    return '<span title="' . e($value) . '">'
                        . substr_replace($value, '...', $maxChars / 2, $textLength - $maxChars)
                        . '</span>';
                }
                return $value;
            },
            'redirect_system' => function ($value) {
                return sprintf(
                    '<span class="%s" title="%s"></span>',
                    $value ? 'oc-icon-magic' : 'oc-icon-user',
                    e(trans('chriss.redirect::lang.redirect.system_tip'))
                );
            },
        ];
    }
}
