<?php
/**
 * @package Magnate
 * @author Dmitry Shumilin (chirontex@yandex.ru)
 */
namespace Magnate\Interfaces;

/**
 * AdminPageInjector interface.
 * @since 0.4.0
 */
interface AdminPageInjectorInterface
{

    /**
     * @since 0.4.0
     * 
     * @param string $slug
     * Page slug.
     * 
     * @param string $view
     * Page view.
     * 
     * @param string $page_title
     * Page title.
     * 
     * @param string $menu_title
     * Menu title.
     * 
     * @param string|int $capability
     * Capability to access.
     * 
     * @param string $parent_slug
     * Parent slug. Optional.
     * 
     * @param string $icon
     * Path to icon. Optional.
     */
    public function __construct(
        string $slug,
        string $view,
        string $page_title,
        string $menu_title,
        $capability,
        string $parent_slug = '',
        string $icon = '');

    /**
     * Get slug.
     * @since 0.4.0
     * 
     * @return string
     */
    public function getSlug() : string;

    /**
     * Get view.
     * @since 0.4.0
     * 
     * @return string
     */
    public function getView() : string;

    /**
     * Get page title.
     * @since 0.4.0
     * 
     * @return string
     */
    public function getPageTitle() : string;

    /**
     * Get menu title.
     * @since 0.4.0
     * 
     * @return string
     */
    public function getMenuTitle() : string;

    /**
     * Get capability.
     * @since 0.4.0
     * 
     * @return string|int
     */
    public function getCapability();

    /**
     * Get parent slug.
     * @since 0.4.0
     * 
     * @return string
     */
    public function getParentSlug() : string;

    /**
     * Get icon.
     * @since 0.4.0
     * 
     * @return string
     */
    public function getIcon() : string;

}
