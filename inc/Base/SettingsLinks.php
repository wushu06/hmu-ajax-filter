<?php

namespace Inc\Base;

use \Inc\Base\BaseController;

class SettingsLinks extends BaseController
{

    public function __construct()
    {
        $this->plugin = $this->plugin;
    }

    public function register()
    {
        add_filter("plugin_action_links_$this->plugin", array($this, 'settingsLink'));
    }

    public function settingsLink($links)
    {
        $settings_link = '<a href="admin.php?page=hookmeup">Settings</a>';
        array_push($links, $settings_link);
        return $links;
    }
}
