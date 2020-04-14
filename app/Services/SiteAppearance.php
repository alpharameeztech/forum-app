<?php

namespace App\Services;

use App\ForumAppearance;

class SiteAppearance
{
    static public function filter($filter)
    {

        $themeSettings = ForumAppearance::first();
        if($themeSettings != null)
        {
            $shopTheme = $themeSettings->theme;

            return $shopTheme->$filter;
        }

    }
}
