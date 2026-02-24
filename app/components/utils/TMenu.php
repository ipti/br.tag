<?php

class TMenu
{
    public static function renderMenu($menuConfig)
    {
        $currentRoute = Yii::app()->controller->route;

        foreach ($menuConfig as $item) {
            // Checagem de permissões
            if (isset($item['roles']) && !TagUtils::checkAccess($item['roles'])) {
                continue;
            }

            // Checagem de feature flag
            if (isset($item['feature']) && $item['feature'] && !Yii::app()->features->isEnable($item['feature'])) {
                continue;
            }

            // Condições extras (exemplo Sagres/INSTANCE)
            if (isset($item['condition']) && is_callable($item['condition']) && !$item['condition']()) {
                continue;
            }

            // URL pode ser string, array [route], ou function
            $urlParams = is_callable($item['url']) ? $item['url']() : $item['url'];
            
            // Extract route from array or string
            $itemRoute = is_array($urlParams) ? trim($urlParams[0], '/') : trim($urlParams, '/');
            if (empty($itemRoute)) $itemRoute = 'site/index';
            
            // Append /index if it's just a controller or module name
            if (strpos($itemRoute, '/') === false) {
                $modules = array_keys(Yii::app()->modules);
                if (in_array($itemRoute, $modules)) {
                    $itemRoute .= '/default/index';
                } else {
                    $itemRoute .= '/index';
                }
            }

            // Check if active
            $isActive = ($currentRoute === $itemRoute);
            $hasActiveSubmenu = (isset($item['submenu']) && self::submenuIsActive($item['submenu'], $currentRoute));

            // Renderiza item simples
            if (!isset($item['submenu'])) {
                $activeClass = $isActive ? 'active' : '';
                $url = Yii::app()->createUrl($itemRoute, is_array($urlParams) && isset($urlParams[1]) ? $urlParams[1] : []);

                echo <<<HTML
                <li class="t-menu-item {$activeClass}">
                    <a class="t-menu-item__link" href="{$url}">
                        <span class="{$item['icon']} t-menu-item__icon"></span>
                        <span class="t-menu-item__text">{$item['label']}</span>
                    </a>
                </li>
                HTML;
            } else {
                // Renderiza grupo com submenu
                $activeClass = $hasActiveSubmenu ? 'active' : '';
                $collapseClass = $hasActiveSubmenu ? 'in' : '';
                
                echo <<<HTML
                <li id="{$item['menu_id']}" class="t-menu-group {$activeClass}">
                    <i class="submenu-icon fa fa-chevron-right"></i>
                    <i class="submenu-icon fa fa-chevron-down"></i>
                    <a id="{$item['menu_id']}-trigger" class="t-menu-group__link toggle-menu-js" data-toggle="collapse" href="#{$item['submenu_id']}">
                        <span class="{$item['icon']} t-menu-item__icon"></span>
                        <span class="t-menu-group__text">{$item['label']}</span>
                    </a>
                    <ul class="collapse {$collapseClass}" id="{$item['submenu_id']}">
                HTML;
                self::renderMenu($item['submenu']);
                echo '</ul></li>';
            }
        }
    }

    protected static function submenuIsActive($submenu, $currentRoute)
    {
        foreach ($submenu as $sub) {
            $urlParams = is_callable($sub['url']) ? $sub['url']() : $sub['url'];
            $subRoute = is_array($urlParams) ? trim($urlParams[0], '/') : trim($urlParams, '/');
            
            if (strpos($subRoute, '/') === false) {
                $modules = array_keys(Yii::app()->modules);
                $subRoute .= in_array($subRoute, $modules) ? '/default/index' : '/index';
            }

            if ($subRoute === $currentRoute) {
                return true;
            }
            
            if (isset($sub['submenu']) && self::submenuIsActive($sub['submenu'], $currentRoute)) {
                return true;
            }
        }
        return false;
    }
}
