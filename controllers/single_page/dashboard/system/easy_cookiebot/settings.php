<?php

namespace Concrete\Package\EasyCookiebot\Controller\SinglePage\Dashboard\System\EasyCookiebot;

use A3020\EasyCookiebot\TrackingCode;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Routing\Redirect;

final class Settings extends DashboardPageController
{
    public function view()
    {
        /** @var Repository $config */
        $config = $this->app->make(Repository::class);

        $this->set('isEnabled', (bool) $config->get('easy_cookiebot.enabled', true));
        $this->set('showForRegisteredUsers', (bool) $config->get('easy_cookiebot.show_for_registered_users', false));
        $this->set('cookiebotId', $config->get('easy_cookiebot.id'));
        $this->set('showChangeToPriorConsent', $this->showChangeToPriorConsent());
    }

    public function save()
    {
        if (!$this->token->validate('a3020.easy_cookiebot.settings')) {
            $this->flash('error', $this->token->getErrorMessage());

            return Redirect::to('/dashboard/system/easy_cookiebot/settings');
        }

        /** @var Repository $config */
        $config = $this->app->make(Repository::class);
        $config->save('easy_cookiebot.enabled', (bool) $this->post('isEnabled'));
        $config->save('easy_cookiebot.id', $this->post('cookiebotId'));
        $config->save('easy_cookiebot.show_for_registered_users', (bool) $this->post('showForRegisteredUsers'));

        $this->flash('success', t('Your settings have been saved.'));

        return Redirect::to('/dashboard/system/easy_cookiebot/settings');
    }

    /**
     * Modify the tracking code to opt-in / prior consent
     */
    public function makePriorConsent()
    {
        if (!$this->token->validate('a3020.easy_cookiebot.make_prior_consent')) {
            $this->flash('error', $this->token->getErrorMessage());

            return Redirect::to('/dashboard/system/easy_cookiebot/settings');
        }

        /** @var TrackingCode $code */
        $code = $this->app->make(TrackingCode::class);

        if ($code->makePriorConsent()) {
            $this->flash('success', t('Your tracking code is now configured as prior consent.'));
        } else {
            $this->flash('error', t('Something went wrong.'));
        }

        return Redirect::to('/dashboard/system/easy_cookiebot/settings');
    }

    /**
     * Whether a button should be visible to make the tracking code opt-in
     *
     * @return bool
     */
    private function showChangeToPriorConsent()
    {
        /** @var TrackingCode $code */
        $code = $this->app->make(TrackingCode::class);

        return !$code->isPriorConsent();
    }
}
