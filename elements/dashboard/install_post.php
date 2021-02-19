<?php

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Support\Facade\Url;
?>
<p><?php echo t('Congratulations, the add-on has been installed!'); ?></p>
<br>

<p>
    <?php
    echo t('To complete the installation, please configure %s.', t('Easy Cookiebot'));
    ?>
</p><br>

<a class="btn btn-primary" href="<?php echo Url::to('/dashboard/system/easy_cookiebot/settings') ?>">
    <?php
    echo t('Open configuration page');
    ?>
</a>
