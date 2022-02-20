<?php

declare(strict_types=1);

use ConcreteCMS\Translate\Navigation\FooterContentProvider;

defined('C5_EXECUTE') or die('Access Denied.');

echo app(FooterContentProvider::class)->getFooterContent();
