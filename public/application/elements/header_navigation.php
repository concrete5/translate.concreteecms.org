<?php

declare(strict_types=1);

use Concrete\Core\Page\Page;
use Concrete\Core\User\User;
use Concrete\Core\Url\Resolver\Manager\ResolverManagerInterface;
use Concrete\Core\Validation\CSRF\Token;

defined('C5_EXECUTE') or die('Access Denied.');

$urlResolver = app(ResolverManagerInterface::class);
$siteConfig = app('site')->getActiveSiteForEditing()->getConfigRepository();

?>
<ul class="nav navbar-nav navbar-right">
    <li class="nav-item index-1">
        <a href="<?= h($urlResolver->resolve(['/about'])) ?>" target="_self" class="nav-link"><?= t('About') ?></a>
    </li>
    <li class="nav-item index-2">
        <a href="<?= h($urlResolver->resolve(['/get-started'])) ?>" target="_self" class="nav-link"><?= t('Get Started') ?></a>
    </li>
    <li class="nav-item index-3">
        <a href="<?= h($urlResolver->resolve(['/extensions'])) ?>" target="_self" class="nav-link"><?= t('Extensions') ?></a>
    </li>
    <li class="nav-item index-3">
        <a href="<?= h($urlResolver->resolve(['/support'])) ?>" target="_self" class="nav-link"><?= t('Support') ?></a>
    </li>
    <li class="nav-item index-5">
        <a href="<?= h($urlResolver->resolve(['/community'])) ?>" target="_self" class="nav-link"><?= t('Community') ?></a>
    </li>
    <?php
    // add search icon
    $searchPageId = $siteConfig->get('concrete_cms_theme.search_page_id');
    if ($searchPageId && is_numeric($searchPageId)) {
        $searchPage = Page::getByID((int) $searchPageId);
        if ($searchPage instanceof Page && !$searchPage->isError()) {
            ?>
            <li class="d-none d-lg-block nav-item">
                <a href="<?= h($urlResolver->resolve([$searchPage])) ?>" title="<?= t('Search') ?>" class="nav-link"><i class="fas fa-search"></i></a>
            </li>
            <?php
        }
    }
    // add user icon
    $user = app(User::class);
    if ($user->isRegistered()) {
        $token = app(Token::class);
        ?>
        <li class="d-none d-lg-block nav-item">
            <a href="<?= h($urlResolver->resolve(['/login', 'do_logout', $token->generate('do_logout')])) ?>" title="<?= t('Sign Out') ?>" class="nav-link"><i class="fas fa-sign-out-alt"></i></a>
        </li>
        <li class="d-block d-lg-none nav-item">
            <a href="<?= h($urlResolver->resolve(['/login', 'do_logout', $token->generate('do_logout')])) ?>" title="<?= t('Sign Out') ?>" class="nav-link"><?= t('Sign Out') ?></a>
        </li>
        <?php
    } else {
        ?>
        <li class="d-none d-lg-block nav-item">
            <a href="<?= h($urlResolver->resolve(['/login'])) ?>" title="<?= t('Sign In') ?>" class="nav-link"><i class="fas fa-sign-in-alt"></i></a>
        </li>
        <li class="d-block d-lg-none nav-item">
            <a href="<?= h($urlResolver->resolve(['/login'])) ?>" title="<?= t('Sign In') ?>" class="nav-link"><?= t('Sign In') ?></a>
        </li>
        <?php
    }
    ?>
</ul>
