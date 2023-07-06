<?php

namespace framework\libraries\owo\interfaces\Inputs;


interface OwoInputValidatorInterface
{
    public function equal(array $keys, $value): self;

    public function pattern(array $keys, string $pattern): self;

    public function slug(array $keys): self;

    public function url(array $keys): self;

    public function email(array $keys): self;

    public function datetime(array $keys, string $format = 'Y-m-d'): self;

    public function string(array $keys): self;

    public function integer(array $keys): self;

    public function length(array $keys, int $length): self;

    public function minmax(array $keys, int $min, int $max): self;

    public function min(array $keys, int $min): self;

    public function max(array $keys, int $max): self;

    public function require(array $keys): self;

    public function validate(array $validations): self;

    public function examine(array $keys, string $rule): self;

    public function hasSucceed(): bool;

    public function getErrors(): array;

    public function resetErrors(): self;

    public function recapErrors(): string;

    public function setInputs(array $inputs): self;

    public function emptyInputs(): self;

    public function addInputs(array $inputs): self;

    public function addInput($key, $value): self;

    public function getInputs(): array;

    public function hasInput($key): bool;

    public function removeInput($key): self;

    public function getInput($key, $default = null);
}
