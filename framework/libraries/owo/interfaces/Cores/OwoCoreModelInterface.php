<?php

namespace framework\libraries\owo\interfaces\Cores;

use framework\libraries\owo\interfaces\Commons\OwoCommonOccurrenceInterface;
use framework\libraries\owo\interfaces\Commons\OwoCommonCapsulatorInterface;


interface OwoCoreModelInterface extends \JsonSerializable, OwoCommonCapsulatorInterface, OwoCommonOccurrenceInterface
{
    public function getDTA(): array;

    public function getId($default = null): int;

    public function digest(array $attributes): self;

    public function alikes(array $matches): bool;

    public function hasNullAttr(string $attr, &$value = null): bool;

    public function set(string $attr, $value): self;

    public function get(string $attr, $default = null);

    public function toJSON(): ?string;

    public function toArray(): array;

    public function create(): ?self;

    public function find(): ?self;

    public function update(): ?self;

    public function delete(): ?self;

    public static function getHiddens(): array;
}
