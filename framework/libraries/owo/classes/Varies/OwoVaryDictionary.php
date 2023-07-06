<?php

namespace framework\libraries\owo\classes\Varies;

use framework\libraries\owo\classes\Helpers\OwoHelperBackrest;
use framework\libraries\owo\classes\Helpers\OwoHelperCapturer;
use framework\libraries\owo\classes\Helpers\OwoHelperString;

use framework\libraries\owo\interfaces\Varies\OwoVaryDictionaryInterface;

use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyStringTermsTrait;
use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringLangTrait;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


class OwoVaryDictionary implements OwoVaryDictionaryInterface
{
    use OwoTakeArrayKeyStringTermsTrait;
    use OwoTakeStringLangTrait;

    use OwoMakeCommonThrowerTrait;

    public const UTILITY_DICTIONARY_DEFAULT_LANG = 'en';

    public function __construct(string $lang = 'en', array $terms = [])
    {
        $this->setLang($lang)->setTerms($terms);
    }

    public function setLangFromGlobals(): self
    {
        $default = static::UTILITY_DICTIONARY_DEFAULT_LANG;
        $lang = OwoHelperCapturer::captureServerClientLang($default);
        return $this->setLang($lang);
    }

    public function loadTermsFromJSON(string $filejson): self
    {
        OwoHelperBackrest::ensureReadable($filejson);
        $terms = OwoHelperBackrest::loadContentsJSON($filejson);
        if (true === \is_null($terms)) {
            static::throwRuntimeException(\sprintf('Load JSON [%s] Failed', $filejson));
        }
        return $this->addTerms($terms);
    }

    public function loadTermsFromDOT(string $filedot): self
    {
        OwoHelperBackrest::ensureReadable($filedot);
        $terms = OwoHelperBackrest::loadContentsDOT($filedot, ['#']);
        if (true === \is_null($terms)) {
            static::throwRuntimeException(\sprintf('Load DOT [%s] Failed', $filedot));
        }
        return $this->addTerms($terms);
    }

    public function loadTermsFromLangJSON(string $dir): self
    {
        $filejson = $dir.'/'.OwoHelperString::upperCase($this->lang).'.json';
        return $this->loadTermsFromJSON($filejson);
    }

    public function loadTermsFromLangDOT(string $dir): self
    {
        $filedot = $dir.'/'.OwoHelperString::upperCase($this->lang);
        return $this->loadTermsFromDOT($filedot);
    }
}
