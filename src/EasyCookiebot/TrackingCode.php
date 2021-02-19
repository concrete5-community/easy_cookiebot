<?php

namespace A3020\EasyCookiebot;

use Concrete\Core\Application\Application;
use Concrete\Core\Entity\Site\Site;

class TrackingCode
{
    /** @var Application */
    private $app;

    /** @return \Concrete\Core\Config\Repository\Liaison */
    protected $config;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->config = $this->getConfig();
    }

    /**
     * Return true if the tags have been modified already
     *
     * @return bool
     */
    public function isPriorConsent()
    {
        $header = $this->config->get('seo.tracking.code.header');
        $footer = $this->config->get('seo.tracking.code.footer');

        // If attribute exists in header or footer, we consider it is configured as 'prior consent'
        return strpos($header, 'data-cookieconsent') !== false
            || strpos($footer, 'data-cookieconsent') !== false;
    }

    /**
     * Changes the header/footer script tracking code
     *
     * @return bool
     */
    public function makePriorConsent()
    {
        if (!$this->has()) {
            return false;
        }

        $header = $this->config->get('seo.tracking.code.header');
        $this->config->save('seo.tracking.code.header', $this->modifyScriptTag($header));

        $footer = $this->config->get('seo.tracking.code.footer');
        $this->config->save('seo.tracking.code.footer', $this->modifyScriptTag($footer));

        return true;
    }

    /**
     * Return true if a header or footer tracking code is configured
     *
     * @return bool
     */
    public function has()
    {
        return $this->config->get('seo.tracking.code.header')
            || $this->config->get('seo.tracking.code.footer');
    }

    /**
     * Returns the site's config
     *
     * @return \Concrete\Core\Config\Repository\Liaison
     */
    private function getConfig()
    {
        /** @var Site $site */
        $site = $this->app->make('site')->getSite();

        return $site->getConfigRepository();
    }

    private function modifyScriptTag($element)
    {
        return str_replace(
            '<script type="text/plain">',
            '<script type="text/plain" data-cookieconsent="statistics">',
            $element
        );
    }
}
