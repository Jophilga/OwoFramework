<?php

namespace framework\libraries\owo\classes\Cores;

use framework\libraries\owo\interfaces\Cores\OwoCoreViewInterface;
use framework\libraries\owo\interfaces\Codes\OwoCodeTemplatorPHPInterface;
use framework\libraries\owo\classes\Casters\OwoCasterDIContainer;

use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyMixeAttributesTrait;


class OwoCoreView implements OwoCoreViewInterface
{
    use OwoTakeArrayKeyMixeAttributesTrait;

    public const CORE_VIEW_SCRIPTS_DIR = 'application/resources/assets/scripts';

    public const CORE_VIEW_STYLES_DIR = 'application/resources/assets/styles';

    public const CORE_VIEW_TEMPLATES_DIR = 'application/resources/assets/templates';

    public const CORE_VIEW_VIEWS_DIR = 'application/resources/assets/views';

    public function __construct(array $attributes = [])
    {
        $this->setAttributes($attributes);
    }

    public static function getScriptPath(string $script): string
    {
        return OWO_ROOT.'/'.static::CORE_VIEW_TEMPLATES_DIR.'/'.$script;
    }

    public static function getStylePath(string $style): string
    {
        return OWO_ROOT.'/'.static::CORE_VIEW_TEMPLATES_DIR.'/'.$style;
    }

    public static function getTemplatePath(string $template): string
    {
        return OWO_ROOT.'/'.static::CORE_VIEW_TEMPLATES_DIR.'/'.$template;
    }

    public static function getViewPath(string $view): string
    {
        return OWO_ROOT.'/'.static::CORE_VIEW_VIEWS_DIR.'/'.$view;
    }

    public static function getTemplator(): OwoCodeTemplatorPHPInterface
    {
        $application = OwoCasterDIContainer::getSingleton();
        $templator = $application->get('Templator::fromTemporay');
        return $templator;
    }
}
