<?php

class TMenu
{
    public static function renderMenu($menuConfig)
    {
        $route = Yii::app()->controller->module ? Yii::app()->controller->module->id : Yii::app()->controller->id;
        $action = Yii::app()->controller->action->id;

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

            $url = Yii::app()->createUrl($urlParams[0], $urlParams[1]);

            $url = strtolower($url);

            $urlAction = count(explode('/', $url)) == 2 ? $url . '/index' : $url;
            $isHome = $route === 'site' && $action === 'index' && $url === '/';
            // Verifica se está ativo
            $isActive = ($isHome || (strpos($url, $route) !== false && strpos($urlAction, $action) !== false) || (isset($item['submenu']) && self::submenuIsActive($item['submenu'], $route)));

            // Renderiza item simples
            if (!isset($item['submenu'])) {
                $iconClass = $isActive ? 'active' : '';
                echo <<<HTML
                <li class="t-menu-item  {$iconClass}">
                    <a class="t-menu-item__link" href="{$url}">
                        <span class="{$item['icon']} t-menu-item__icon"></span>
                        <span class="t-menu-item__text">{$item['label']}</span>
                    </a>
                </li>
                HTML;
            } else {
                // Renderiza grupo com submenu
                $isActive = (isset($item['submenu']) && self::submenuIsActive($item['submenu'], $route));
                $collapseClass = $isActive ? 'in' : '';
                $iconClass = $isActive ? 'active' : '';
                echo <<<HTML
                <li id="{$item['menu_id']}" class="t-menu-group {$iconClass}">
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

    protected static function submenuIsActive($submenu, $route)
    {
        foreach ($submenu as $sub) {
            $url = is_callable($sub['url']) ? $sub['url']()[0] : (is_array($sub['url']) ? $sub['url'][0] : $sub['url']);
            if (strpos($url, $route) !== false) {
                return true;
            }
            if (isset($sub['submenu']) && self::submenuIsActive($sub['submenu'], $route)) {
                return true;
            }
        }
        return false;
    }
}
