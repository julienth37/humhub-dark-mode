<?php

namespace humhub\modules\darkMode;

use humhub\modules\darkMode\widgets\DarkStyle;
use humhub\modules\darkMode\Module;
use humhub\modules\darkMode\widgets\SwitchButton;
use humhub\components\ModuleEvent;
use Yii;

class Events
{
    public static function onLayoutAddonsInit($event)
    {
        $event->sender->addWidget(DarkStyle::class);
    }
    
    public static function onNotificationAddonInit($event)
    {
        try {
            $event->sender->addWidget(SwitchButton::class, [], ['sortOrder' => 200]);
        } catch (\Throwable $e) {
            Yii::error($e);
        }
    }
    
    public static function onAfterModuleEnabled(ModuleEvent $event)
    {
        // If module ID contains "theme" we assume it is a theme module
        if(strpos($event->moduleId, 'theme') !== false) {
            // Delete module setting to prevent design issues
            $settings = Yii::$app->getModule('dark-mode')->settings;
            $settings->delete('theme');
        }
    }

    public static function onBeforeModuleDisabled(ModuleEvent $event)
    {
        $settings = Yii::$app->getModule('dark-mode')->settings;
        $darkTheme = $settings->get('theme');
        
        // If Enterprise was disabled, remove "DarkEnterprise" setting
        if ($event->moduleId == 'enterprise-theme' && $darkTheme == 'DarkEnterprise') {
            $settings->delete('theme');
        }
    }    
}
