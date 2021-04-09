<?php

defined('C5_EXECUTE') or die("Access Denied.");

$urlManager = app(\PortlandLabs\ConcreteCmsTheme\Navigation\UrlManager::class);
$page = Page::getCurrentPage();

?>

<div id="ccm-sub-nav">
    <div class="container">
        <div class="row">
            <div class="col">
                <a href="<?=$urlManager->getTranslateUrl()?>"><h3><?=t('Translate')?></h3></a>

                <nav>
                    <ul>
                        <li <?php if ($page && $page->getCollectionPath() == '/teams') { ?>class="active"<?php } ?>>
                            <a href="<?= Url::to("/teams"); ?>" target="_self">
                                <?php echo t("Translation Teams"); ?>
                            </a>
                        </li>

                        <li <?php if ($page && $page->getCollectionPath() == '/translate') { ?>class="active"<?php } ?>>
                            <a href="<?= Url::to("/translate"); ?>" target="_self">
                                <?php echo t("Translate"); ?>
                            </a>
                        </li>

                        <li <?php if ($page && $page->getCollectionPath() == '/translate-your-packages') { ?>class="active"<?php } ?>>
                            <a href="<?= Url::to("/translate-your-packages"); ?>" target="_self">
                                <?php echo t("Translate your packages"); ?>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

