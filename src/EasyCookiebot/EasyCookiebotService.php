<?php

namespace A3020\EasyCookiebot;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Http\Request;
use Concrete\Core\Http\ResponseAssetGroup;
use Concrete\Core\User\User;

final class EasyCookiebotService implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var Repository
     */
    private $config;

    /**
     * @var Request
     */
    private $request;

    public function __construct(Repository $repository, Request $request)
    {
        $this->config = $repository;
        $this->request = $request;
    }

    public function boot()
    {
        // If Cookiebot is not enabled, we don't do anything
        if ((bool) $this->config->get('easy_cookiebot.enabled', true) === false) {
            return;
        }

        // If the Cookiebot ID is empty, we don't do anything
        $id = $this->getCookiebotId();
        if (empty($id)) {
            return;
        }

        // Disable in admin area
        foreach([
            '/dashboard',
            '/index.php/tools',
        ] as $needle) {
            if (stripos($this->request->getRequestUri(), $needle) !== false) {
                return;
            }
        }

        // Disable for AJAX requests
        if ($this->request->isXmlHttpRequest()) {
            return;
        }

        if ($this->isWhitelisted()) {
            return;
        }

        // Disable for Registered Users (if configured like that)
        if ((bool) $this->config->get('easy_cookiebot.show_for_registered_users', false) === false) {
            $user = new User();
            if ($user->isRegistered()) {
                return;
            }
        }

        // Add the Cookiebot script to the HTML output (in the head)
        $assetGroup = ResponseAssetGroup::get();
        $assetGroup->addHeaderAsset($this->getCookiebotSnippet($id));
    }

    /*
     * Get the Cookiebot JavaScript snippet
     *
     * @param string $id
     */
    private function getCookiebotSnippet($id)
    {
        return sprintf('<script id="Cookiebot" src="https://consent.cookiebot.com/uc.js" '.
        'data-cbid="%s" type="text/javascript" async></script>', $id);
    }

    /**
     * Get the Cookiebot ID
     *
     * @return string|null
     */
    private function getCookiebotId()
    {
        return $this->config->get('easy_cookiebot.id');
    }

    /**
     * Whitelisted pages won't get the cookie consent bar
     *
     * E.g. on the /login page
     *
     * @return bool
     */
    private function isWhitelisted()
    {
        $disabledPages = $this->config->get('easy_cookiebot::whitelist', []);
        if (empty($disabledPages)) {
            return false;
        }

        $requestUri = str_replace('/index.php', '', $this->request->getRequestUri());
        $requestUri = rtrim($requestUri, '/');

        return in_array($requestUri, $disabledPages);
    }
}
