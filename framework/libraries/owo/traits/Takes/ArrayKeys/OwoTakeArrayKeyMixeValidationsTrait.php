<?php

namespace framework\libraries\owo\traits\Takes\ArrayKeys;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;


trait OwoTakeArrayKeyMixeValidationsTrait
{
    protected $validations = [];

    public function __construct(array $validations = [])
    {
        $this->setValidations($validations);
    }

    public function setValidations(array $validations): self
    {
        return $this->emptyValidations()->addValidations($validations);
    }

    public function emptyValidations(): self
    {
        $this->validations = [];
        return $this;
    }

    public function addValidations(array $validations): self
    {
        foreach ($validations as $key => $value) $this->addValidation($key, $value);
        return $this;
    }

    public function addValidation($key, $value): self
    {
        $this->validations[$key] = $value;
        return $this;
    }

    public function getValidations(): array
    {
        return $this->validations;
    }

    public function hasValidation($key): bool
    {
        return (true === OwoHelperArray::hasSetKey($this->validations, $key));
    }

    public function removeValidation($key): self
    {
        unset($this->validations[$key]);
        return $this;
    }

    public function getValidation($key, $default = null)
    {
        return $this->validations[$key] ?? $default;
    }
}
