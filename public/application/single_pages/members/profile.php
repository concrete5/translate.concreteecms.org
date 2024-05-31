<?php

use CommunityTranslation\Repository\Locale as LocaleRepository;
use CommunityTranslation\Service\Access;
use Concrete\Core\Area\Area;
use Concrete\Core\Attribute\Key\UserKey;
use Concrete\Core\Block\Block;
use Concrete\Core\Database\Connection\Connection;
use Concrete\Core\Localization\Service\Date;
use Concrete\Core\Url\Resolver\Manager\ResolverManagerInterface;

defined('C5_EXECUTE') or die('Access Denied.');

/**
 * @var Concrete\Core\Page\Page $c
 * @var Concrete\Core\Page\View\PageView $view
 * @var Concrete\Core\Html\Service\Html $html
 * @var Concrete\Controller\SinglePage\Members\Profile $controller
 * @var Concrete\Core\User\UserInfo $profile
 * @var Concrete\Core\Legacy\Avatar $av
 * @var array $badges
 * @var bool $canEdit
 * @var Concrete\Core\Utility\Service\Text $t
 * @var Concrete\Package\ConcreteCmsTheme\Theme\ConcreteCms\PageTheme $theme
 */

$dh = app(Date::class);
$url = app(ResolverManagerInterface::class);
$db = app(Connection::class);

$coreProfileURL = '';
foreach (['external_concrete', 'community'] as $authenticationType) {
    $concreteUserID = $db->fetchOne(
        'SELECT binding FROM OauthUserMap WHERE namespace = ? AND user_id = ? LIMIT 1',
        [$authenticationType, $profile->getUserID()]
    );
    if ($concreteUserID && is_numeric($concreteUserID)) {
        $coreProfileURL = 'https://community.concretecms.com/members/profile/' . (int) $concreteUserID;
        break;
    }
}

?>
<div class="ccm-profile-header">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="ccm-profile-avatar">
                    <?php echo $profile->getUserAvatar()->output(); ?>
                </div>
                <div class="ccm-profile-username">
                    <h1><?=$profile->getUserName()?></h1>
                    <div class="ccm-profile-statistics">
                        <div class="ccm-profile-statistics-item">
                            <i class="fas fa-calendar-alt"></i> <?=t(/*i18n: %s is a date */'Joined on %s', $dh->formatDate($profile->getUserDateAdded(), true))?>
                        </div>
                    </div>
                </div>
                <div class="ccm-profile-buttons">
                    <div class="btn-group mb-3">
                        <?php
                        if ($coreProfileURL !== '') {
                            ?>
                            <a href="<?= h($coreProfileURL) ?>" class="btn btn-lg btn-outline-secondary" target="_blank"><i class="fa-user fa"></i> <?= t('View concrete profile') ?></a>
                            <?php
                        }
                        if ($canEdit) {
                            ?>
                            <a href="<?= $view->url('/account/edit_profile') ?>" class="btn btn-lg btn-outline-secondary"><i class="fas fa-cog"></i> <?= t('Edit') ?></a>
                            <?php
                        }
                        ?>
                        <a href="<?= $view->url('/') ?>" class="btn btn-lg btn-outline-secondary"><i class="fas fa-home"></i> <?= t('Home') ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ccm-profile-detail">
    <div class="container">
        <div class="row">
            <?php
            if (class_exists(Access::class) && $profile->getUserID() != USER_SUPER_ID) {
                ?>
                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-header">
                            <?=t('Translator Information')?>
                        </div>
                        <div class="card-body">
                            <?php
                            $access = app(Access::class);
                            $localeRepository = app(LocaleRepository::class);
                            $localeAccessList = [];
                            $teamsPage = false;
                            foreach ($localeRepository->getApprovedLocales() as $locale) {
                                $la = $access->getLocaleAccess($locale, $profile->getEntityObject());
                                if ($la >= Access::GLOBAL_ADMIN) {
                                    $localeAccessList = Access::GLOBAL_ADMIN;
                                    break;
                                } else {
                                    switch ($la) {
                                        case Access::ADMIN:
                                            $text = tc('User is...', 'a team coordinator for %s');
                                            break;
                                        case Access::TRANSLATE:
                                            $text = tc('User is...', 'a translator for %s');
                                            break;
                                        case Access::ASPRIRING:
                                            $text = tc('User is...', 'an aspiring translator for %s');
                                            break;
                                        default:
                                            $text = null;
                                            break;
                                    }
                                    if ($text !== null) {
                                        if ($teamsPage === false) {
                                            $teamsPage = null;
                                            $block = Block::getByName('CommunityTranslation Team List');
                                            if ($block && $block->getBlockID()) {
                                                $p = $block->getOriginalCollection();
                                                if ($p && !$p->isError()) {
                                                    $teamsPage = $p;
                                                }
                                            }
                                        }
                                        if ($teamsPage === null) {
                                            $text = sprintf($text, h($locale->getDisplayName()));
                                        } else {
                                            $text = sprintf($text, sprintf('<a href="%s">%s</a>', $url->resolve([$teamsPage, 'details', $locale->getID()]), h($locale->getDisplayName())));
                                        }
                                        $localeAccessList[] = $text;
                                    }
                                }
                            }
                            if ($localeAccessList === []) {
                                ?>
                                <p class="card-text lead"><?= t("The user doesn't belong to any translation team.") ?></p>
                                <?php
                            } else {
                                if ($localeAccessList === Access::GLOBAL_ADMIN) {
                                    ?><p><?= t('%s is a site maintainer: your last hope to solve localization-related issues.', h($profile->getUserName())) ?></p><?php
                                } else {
                                    ?><p><?= tc('User is...', '%1$s is %2$s.', h($profile->getUserName()), Punic\Misc::join($localeAccessList)) ?></p><?php
                                }
                                $totalTranslations = 0;
                                $currentTranslations = 0;
                                $rs = $db->executeQuery('select count(*) as n, current from CommunityTranslationTranslations where createdBy = ? group by current', [$profile->getUserID()]);
                                while (($row = $rs->fetch()) !== false) {
                                    if ($row['current']) {
                                        $currentTranslations = (int) $row['n'];
                                        $totalTranslations += $currentTranslations;
                                    } else {
                                        $totalTranslations += (int) $row['n'];
                                    }
                                }
                                if ($totalTranslations === 0) {
                                    ?><p><?= t('%s has not yet contributed any translation.', h($profile->getUserName())) ?></p><?php
                                } else {
                                    ?><p><?=
                                        t2(
                                            '%2$s has contributed with %1$d translation',
                                            '%2$s has contributed with %1$d translations',
                                            $totalTranslations,
                                            h($profile->getUserName())
                                        )
                                        . ' ' .
                                        t2(
                                            '(%d of which is a currently approved translation)',
                                            '(%d of which are currently approved translations)',
                                            $currentTranslations
                                        )
                                    ?>.</p><?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="col-md-12">
                <div class="card mb-3">
                    <div class="card-header">
                        <?=t('Profile Information')?>
                    </div>
                    <div class="card-body">
                        <?php
                        $uaks = UserKey::getPublicProfileList();
                        if ($uaks === []) {
                            ?>
                            <p class="card-text lead"><?= t('There is no public user information available.') ?></p>
                            <?php
                        } else {
                            foreach ($uaks as $ua) {
                                $r = $profile->getAttribute($ua, 'displaySanitized', 'display');
                                ?>
                                <div>
                                    <h4><?= $ua->getAttributeKeyDisplayName() ?></h4>
                                    <?= $r ?: t('None') ?>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
                <?php
                $a = new Area('Main');
                $a->setBlockWrapperStart('<div class="ccm-profile-body-item">');
                $a->setBlockWrapperEnd('</div>');
                $a->display($c);
                ?>
            </div>
        </div>
    </div>
</div>
