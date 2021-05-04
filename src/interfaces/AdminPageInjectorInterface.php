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
        string $icon = '',
        string $position = '');

    /**
     * Add an additional JS script to page.
     * @since 0.6.5
     * 
     * @param string $name
     * Script unique name.
     * 
     * @param string $src
     * Source.
     * 
     * @param array $dependencies
     * Scripts to be loaded before this script.
     * 
     * @param string version
     * Script version.
     * 
     * @param bool $in_footer
     * Determines whether to add script to footer.
     */
    public function addScript(
        string $name,
        string $src,
        array $dependecies = [],
        string $version = '',
        bool $in_footer = false) : self;

    /**
     * Add an additional CSS to page.
     * @since 0.6.5
     * 
     * @param string $name
     * Style unique name.
     * 
     * @param string $src
     * Source.
     * 
     * @param array $dependencies
     * Styles to be loaded before this style.
     * 
     * @param string $version
     * Style version.
     * 
     * @param string $media
     * Value for the 'media' atribute.
     */
    public function addStyle(
        string $name,
        string $src,
        array $dependecies = [],
        string $version = '',
        string $media = 'all') : self;

    /**
     * Get scripts.
     * @since 0.6.5
     * 
     * @return array
     */
    public function getScripts() : array;

    /**
     * Get styles.
     * @since 0.6.5
     * 
     * @return array
     */
    public function getStyles() : array;

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

    /**
     * Get menu item position.
     * @since 0.5.0
     * 
     * @return int|float|string
     */
    public function getPosition();

}
