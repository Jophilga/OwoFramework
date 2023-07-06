<?php

namespace framework\libraries\owo\classes\Queries;

use framework\libraries\owo\interfaces\Queries\OwoQueryBuilderInterface;

use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyStringSectionsTrait;
use framework\libraries\owo\traits\Takes\Booleans\OwoTakeBooleanAbstractTrait;


class OwoQueryBuilder implements OwoQueryBuilderInterface
{
    use OwoTakeArrayKeyStringSectionsTrait;
    use OwoTakeBooleanAbstractTrait;

    protected $prepared_params = [];

    public function __construct(array $sections = [], bool $abstract = true)
    {
        $this->setSections($sections)->setAbstract($abstract);
    }

    public function getPreparedParams(): array
    {
        return $this->prepared_params;
    }

    public function assignSections(array $sections, string $combiner = ','): self
    {
        foreach ($sections as $key => $value) {
            $this->assignSection($key, $value, $combiner);
        }
        return $this;
    }

    public function assignSection($key, string $section, string $combiner = ','): self
    {
        if (true === $this->hasSection($key)) {
            $section = \sprintf('%s %s %s', $this->getSection($key), $combiner, $section);
        }
        return $this->addSection($key, $section);
    }

    public function getQuery(): string
    {
        $lines = [];
        foreach ($this->sections as $key => $value) {
            if (true === \is_string($key)) {
                $lines[] = \sprintf('%s %s', \strval($key), \strval($value));
            }
            else $lines[] = \strval($value);
        }
        return \implode(\PHP_EOL, $lines);
    }
}
