<?php

namespace Concrete\Package\EasyCookiebot\Controller\SinglePage\Dashboard\System;

use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Routing\Redirect;

final class EasyCookiebot extends DashboardPageController
{
    public function view()
    {
        return Redirect::to('/dashboard/system/easy_cookiebot/settings');
    }
}
