<?php

namespace Concrete\Package\EasyCookiebot;

use A3020\EasyCookiebot\EasyCookiebotService;
use A3020\EasyCookiebot\Installer\Installer;
use Concrete\Core\Package\Package;
use Concrete\Core\Support\Facade\Package as PackageFacade;

final class Controller extends Package
{
    protected $pkgHandle = 'easy_cookiebot';
    protected $appVersionRequired = '8.0';
    protected $pkgVersion = '0.9.2';
    protected $pkgAutoloaderRegistries = [
        'src/EasyCookiebot' => '\A3020\EasyCookiebot',
    ];

    public function getPackageName()
    {
        return t('Easy Cookiebot');
    }

    public function getPackageDescription()
    {
        return t('A simple and fast integration with cookiebot.com.');
    }

    public function on_start()
    {
        $provider = $this->app->make(EasyCookiebotService::class);
        $provider->boot();
    }

    public function install()
    {
        $pkg = parent::install();

        $installer = $this->app->make(Installer::class);
        $installer->install($pkg);
    }

    public function upgrade()
    {
        parent::upgrade();

        /** @see \Concrete\Core\Package\PackageService */
        $pkg = PackageFacade::getByHandle($this->pkgHandle);

        $installer = $this->app->make(Installer::class);
        $installer->install($pkg);
    }
}
