<?php namespace Humweb\ThemeManager;

use Humweb\Modules\Settings\SettingsSchema;

class ThemeSettingsSchema extends SettingsSchema {

    function __construct($values = [], $decorator = null)
    {

        parent::__construct($values, $decorator);

        $this->settings = [
            'theme.current' => [
                'type' => 'select',
                'label' => 'Theme',
                'description' => 'Select theme for site',
                'options' => [
                    'default' => 'Default'
                ],
            ]
        ];
    }
}
