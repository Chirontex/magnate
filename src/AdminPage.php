<?php
/**
 * @package Magnate
 * @author Dmitry Shumilin (chirontex@yandex.ru)
 */
namespace Magnate;

use Magnate\Injectors\EntryPointInjector;
use Magnate\Injectors\AdminPageInjector;

/**
 * @abstract
 * Admin page entry point class.
 * @since 0.4.0
 */
abstract class AdminPage extends EntryPoint
{

    /**
     * @var string $slug
     * Page slug.
     * @since 0.5.0
     */
    protected $slug = '';

    /**
     * @var string $view
     * Path to the page view.
     * @since 0.5.0
     */
    protected $view = '';

    /**
     * @var string $page_title
     * Page title.
     * @since 0.5.0
     */
    protected $page_title = '';

    /**
     * @var string $menu_title
     * Menu title.
     * @since 0.5.0
     */
    protected $menu_title = '';

    /**
     * @var int|string $capability
     * Capability to access.
     * @since 0.5.0
     */
    protected $capability = '';

    /**
     * @var string $parent_slug
     * Parent slug.
     * @since 0.5.0
     */
    protected $parent_slug = '';

    /**
     * @var string $icon
     * Path to icon.
     * @since 0.5.0
     */
    protected $icon = '';

    /**
     * @var int|float|string $position
     * Item menu position.
     * @since 0.5.0
     */
    protected $position = '';

    /**
     * @since 0.5.0
     * 
     * @param EntryPointInjector $ep_injector
     * Parent class injector.
     * 
     * @param AdminPageInjector $ap_injector
     * Injector for this class.
     */
    public function __construct(EntryPointInjector $ep_injector, AdminPageInjector $ap_injector)
    {

        $this->slug = $ap_injector->getSlug();
        $this->view = $ap_injector->getView();
        $this->page_title = $ap_injector->getPageTitle();
        $this->menu_title = $ap_injector->getMenuTitle();
        $this->capability = $ap_injector->getCapability();
        $this->parent_slug = $ap_injector->getParentSlug();
        $this->icon = $ap_injector->getIcon();
        $this->position = $ap_injector->getPosition();
        
        parent::__construct($ep_injector);

    }

    /**
     * Add page to admin menu.
     * @since 0.5.0
     * 
     * @return $this
     */
    protected function addToMenu() : self
    {

        add_action('admin_menu', function() {

            add_menu_page(
                $this->page_title,
                $this->menu_title,
                $this->capability,
                $this->slug,
                function() {

                    if (!empty($this->view)) $this->plugView();

                },
                $this->icon,
                $this->position
            );

            remove_submenu_page($this->slug, $this->slug);

        });

        return $this;

    }

    /**
     * Add page to submenu.
     * @since 0.6.0
     * 
     * @return $this
     */
    protected function addToSubmenu() : self
    {

        add_action('admin_menu', function() {

            $menu_title = empty($this->icon) ?
                '' : file_get_contents($this->icon).' ';
            $menu_title .= $this->menu_title;

            add_submenu_page(
                $this->parent_slug,
                $this->page_title,
                $menu_title,
                $this->capability,
                $this->slug,
                function() {

                    if (!empty($this->view)) $this->plugView();

                },
                $this->position
            );

        });

        return $this;

    }

    /**
     * Add view to the view callback.
     * @since 0.6.0
     * 
     * @return $this
     */
    protected function plugView() : self
    {

        $path = $this->path;
        $url = $this->url;
        $title = $this->page_title;

        do_action('magnate-adminpage-notice');

        require_once $this->view;

        return $this;

    }

}
