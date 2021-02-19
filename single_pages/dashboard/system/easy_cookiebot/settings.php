<?php
defined('C5_EXECUTE') or die('Access Denied.');
?>

<div class="ccm-dashboard-content-inner">
    <p>
        <?php
        echo t('Cookiebot enables your website to comply with current legislation in the EU on the use of cookies for user tracking and profiling. The EU ePrivacy Directive requires prior, informed consent of your site users, while the <a href="https://www.cookiebot.com/en/gdpr" target="_blank">General Data Protection Regulation (GDPR)</a> requires you to document each consent. At the same time you must be able to account for what user data you share with embedded third-party services on your website and where in the world the user data is sent.');
        ?>
    </p>

    <hr>

    <form method="post" action="<?php echo $this->action('save'); ?>">
        <?php
        /** @var $token \Concrete\Core\Validation\CSRF\Token */
        echo $token->output('a3020.easy_cookiebot.settings');
        ?>

        <div class="form-group">
            <label class="control-label launch-tooltip"
                   title="<?php echo t("If disabled, Cookiebot will be completely turned off.") ?>"
                   for="isEnabled">
                <?php
                /** @var bool $isEnabled */
                echo $form->checkbox('isEnabled', 1, $isEnabled);
                ?>
                <?php echo t('Enable Cookiebot'); ?>
            </label>
        </div>

        <div class="form-group">
            <label class="control-label launch-tooltip"
                   title="<?php echo t("If checked, Registered Users will also see the Cookiebot bar.") ?>"
                   for="showForRegisteredUsers">
                <?php
                /** @var bool $showForRegisteredUsers */
                echo $form->checkbox('showForRegisteredUsers', 1, $showForRegisteredUsers);
                ?>
                <?php echo t('Show for Registered Users'); ?>
            </label>
        </div>

        <div class="form-group">
            <label class="control-label launch-tooltip"
                   title="<?php echo t('.'); ?>"
                   for="cookiebotId">
                <?php
                echo t('Cookiebot ID');
                ?>
            </label>

            <?php
            /** @var string $cookiebotId */
            echo $form->text('cookiebotId', $cookiebotId, [
                'placeholder' => t('E.g. 48ef7e97-e56c-412b-a7fe-6f5882dfg459d'),
            ]);
            ?>

            <p class="text-muted small" style="margin-top: 5px;">
                <?php echo t('Need an ID?'); ?>

                <a href="https://www.cookiebot.com/en/signup" target="_blank">
                    <?php echo t('Sign up for free on cookiebot.com'); ?>
                </a>
            </p>
        </div>

        <div class="ccm-dashboard-form-actions-wrapper">
            <div class="ccm-dashboard-form-actions">
                <button class="pull-right btn btn-primary" type="submit"><?php echo t('Save') ?></button>
            </div>
        </div>
    </form>

    <?php
    /** @var bool $showChangeToPriorConsent */
    if ($showChangeToPriorConsent) {
        ?>
        <hr>

        <form action="<?php echo $this->action('makePriorConsent'); ?>" method="post">
            <?php
            /** @var $token \Concrete\Core\Validation\CSRF\Token */
            echo $token->output('a3020.easy_cookiebot.make_prior_consent');
            ?>

            <div class="well">
                <p>
                    <?php
                    echo t("Your current tracking code tracks visitors even though they haven't given consent. Do you want to change the tracking code to comply with %sprior consent%s?",
                        '<a href="https://support.cookiebot.com/hc/en-us/articles/360004104033-What-does-prior-consent-mean-and-how-do-I-implement-it-" target="_blank">',
                        '</a>'
                    );
                    ?>
                    <br>
                    <small class="text-muted">
                        <?php echo t("By doing so, a %s attribute will be added to the script tags found in the header and footer tracking codes.", 'data-cookieconsent="statistics"'); ?>
                    </small>
                </p>

                <input type="submit" class="btn btn-default" value="<?php echo t('Yes, change tracking code to prior consent'); ?>">
            </div>
        </form>
        <?php
    }
    ?>
</div>
