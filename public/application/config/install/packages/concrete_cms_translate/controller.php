<?php
namespace Application\StartingPointPackage\ConcreteCmsTranslate;

use Concrete\Core\Package\StartingPointPackage;

class Controller extends StartingPointPackage
{
    protected $pkgHandle = 'concrete_cms_translate';

    public function getPackageName()
    {
        return t('translate.concretecms.org');
    }

    public function getPackageDescription()
    {
        return 'Installs the translate.concretecms.org starting point.';
    }
    
}
