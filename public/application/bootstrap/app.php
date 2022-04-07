<?php

declare(strict_types=1);

use Concrete\Core\Events\EventDispatcher;
use Concrete\Core\User\User;
use PortlandLabs\CommunityBadgesClient\Models\Achievements;
use PortlandLabs\ConcreteCmsTheme\Navigation\HeaderNavigationFactory;
use Symfony\Component\EventDispatcher\GenericEvent;

defined('C5_EXECUTE') or die('Access Denied.');

/**
 * @var Concrete\Core\Application\Application $app
 */

$eventDispatcher = $app->make(EventDispatcher::class);

$eventDispatcher->addListener('on_before_render', static function($event) use ($app): void {
    // must be done in an event because it must come AFTER the concrete cms package registers the
    // header navigation factory class as a singleton.
    //
    // needs to check first if the class exists. because when it's a fresh installation the installer won't run
    if (class_exists(HeaderNavigationFactory::class)) {
        $headerNavigationFactory = $app->make(HeaderNavigationFactory::class);
        $headerNavigationFactory->setActiveSection(HeaderNavigationFactory::SECTION_COMMUNITY);
    }
});

$eventDispatcher->addListener('community_translation.translation_submitted', static function(GenericEvent $event) use ($app): void {
    $subject = $event->getSubject();
    if ($subject instanceof User) {
        $user = $subject;
    } else {
        if (is_object($subject) && method_exists($subject, 'getUserID')) {
            $userID = (int) $subject->getUserID();
        } elseif (is_numeric($subject)) {
            $userID = (int) $subject;
        } else {
            return;
        }
        $user = User::getByUserID($userID);
    }
    if ($user !== null) {
        $achievements = $app->make(Achievements::class, ['user' => $user]);
        $achievements->assign('translator');
    }
});

$eventDispatcher->addListener('community_translation.user_became_coordinator', static function(GenericEvent $event) use ($app): void {
    $subject = $event->getSubject();
    if ($subject instanceof User) {
        $user = $subject;
    } else {
        if (is_object($subject) && method_exists($subject, 'getUserID')) {
            $userID = (int) $subject->getUserID();
        } elseif (is_numeric($subject)) {
            $userID = (int) $subject;
        } else {
            return;
        }
        $user = User::getByUserID($userID);
    }
    if ($user !== null) {
        $achievements = $app->make(Achievements::class, ['user' => $user]);
        $achievements->assign('coordinator');
    }
});
