<?php

namespace A3020\EasyCookiebot\Installer;

use Concrete\Core\Page\Page;
use Concrete\Core\Page\Single;

class Installer
{
    /**
     * @param \Concrete\Core\Package\Package $pkg
     */
    public function install($pkg)
    {
        $pages = [
            '/dashboard/system/easy_cookiebot' => t('Easy Cookiebot'),
            '/dashboard/system/easy_cookiebot/settings' => t('Settings'),
        ];

        // Using for loop because additional pages
        // may be added in the future.
        foreach ($pages as $path => $name) {
            /** @var Page $page */
            $page = Page::getByPath($path);
            if ($page && !$page->isError()) {
                continue;
            }

            $singlePage = Single::add($path, $pkg);
            $singlePage->update([
                'cName' => $name,
            ]);
        }
    }
}
