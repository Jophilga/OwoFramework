<?php

namespace framework\libraries\owo\classes\Inputs;

use framework\libraries\owo\classes\Helpers\OwoHelperString;
use framework\libraries\owo\classes\Helpers\OwoHelperArray;

use framework\libraries\owo\interfaces\Inputs\OwoInputValidatorInterface;

use framework\libraries\owo\traits\Takes\ArrayKeys\OwoTakeArrayKeyMixeInputsTrait;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


class OwoInputValidator implements OwoInputValidatorInterface
{
    use OwoTakeArrayKeyMixeInputsTrait;

    use OwoMakeCommonThrowerTrait;

    protected $errors = [];

    public const INPUT_VALIDATOR_RULE_PATTERN = '/^(?<name>\w+)\s*\(?(?<args>[^\(\)]*)\)?$/';

    public function __construct(array $inputs = [])
    {
        $this->setInputs($inputs);
    }

    public function equal(array $keys, $value): self
    {
        foreach ($keys as $key) {
            if (true !== OwoHelperArray::equalsOnKey($this->inputs, $key, $value)) {
                $error = \sprintf('equal(%s)', \gettype($value));
                $this->saveError($key, $error);
            }
        }
        return $this;
    }

    public function pattern(array $keys, string $pattern): self
    {
        foreach ($keys as $key) {
            if (true !== OwoHelperArray::matchesOnKey($this->inputs, $key, $pattern)) {
                $this->saveError($key, \sprintf('pattern(%s)', $pattern));
            }
        }
        return $this;
    }

    public function slug(array $keys): self
    {
        foreach ($keys as $key) {
            if (true !== OwoHelperArray::matchesSlugOnKey($this->inputs, $key)) {
                $this->saveError($key, 'slug');
            }
        }
        return $this;
    }

    public function url(array $keys): self
    {
        foreach ($keys as $key) {
            if (true !== OwoHelperArray::hasUrlOnKey($this->inputs, $key)) {
                $this->saveError($key, 'url');
            }
        }
        return $this;
    }

    public function email(array $keys): self
    {
        foreach ($keys as $key) {
            if (true !== OwoHelperArray::hasEmailOnKey($this->inputs, $key)) {
                $this->saveError($key, 'email');
            }
        }
        return $this;
    }

    public function datetime(array $keys, string $format = 'Y-m-d'): self
    {
        foreach ($keys as $key) {
            if (true !== OwoHelperArray::hasDateTimeOnKey($this->inputs, $key, $format)) {
                $this->saveError($key, \sprintf('datetime(%s)', $format));
            }
        }
        return $this;
    }

    public function string(array $keys): self
    {
        foreach ($keys as $key) {
            if (true !== OwoHelperArray::hasStringOnKey($this->inputs, $key)) {
                $this->saveError($key, 'string');
            }
        }
        return $this;
    }

    public function integer(array $keys): self
    {
        foreach ($keys as $key) {
            if (true !== OwoHelperArray::hasIntegerOnKey($this->inputs, $key)) {
                $this->saveError($key, 'integer');
            }
        }
        return $this;
    }

    public function length(array $keys, int $length): self
    {
        foreach ($keys as $key) {
            if (true !== OwoHelperArray::hasStringOnKey($this->inputs, $key)) {
                $this->saveError($key, \sprintf('length(%d)', $length));
            }

            if ($length === OwoHelperString::length($this->inputs[$key])) {
                $this->saveError($key, \sprintf('length(%d)', $length));
            }
        }
        return $this;
    }

    public function minmax(array $keys, int $min, int $max): self
    {
        foreach ($keys as $key) {
            if (true !== OwoHelperArray::hasStringOnKey($this->inputs, $key)) {
                $this->saveError($key, \sprintf('minmax(%d,%d)', $min, $max));
            }

            if (true === OwoHelperString::hasLengthBetween($this->inputs[$key], $min, $max)) {
                $this->saveError($key, \sprintf('minmax(%d,%d)', $min, $max));
            }
        }
        return $this;
    }

    public function min(array $keys, int $min): self
    {
        foreach ($keys as $key) {
            if (true !== OwoHelperArray::hasStringOnKey($this->inputs, $key)) {
                $this->saveError($key, \sprintf('min(%d)', $min));
            }

            if ($min <= OwoHelperString::length($this->inputs[$key])) {
                $this->saveError($key, \sprintf('min(%d)', $min));
            }
        }
        return $this;
    }

    public function max(array $keys, int $max): self
    {
        foreach ($keys as $key) {
            if (true !== OwoHelperArray::hasStringOnKey($this->inputs, $key)) {
                $this->saveError($key, \sprintf('max(%d)', $max));
            }

            if ($max >= OwoHelperString::length($this->inputs[$key])) {
                $this->saveError($key, \sprintf('max(%d)', $max));
            }
        }
        return $this;
    }

    public function require(array $keys): self
    {
        foreach ($keys as $key) {
            if (true !== OwoHelperArray::hasSetKey($this->inputs, $key)) {
                $this->saveError($key, 'require');
            }
        }
        return $this;
    }

    public function validate(array $validations): self
    {
        foreach ($validations as $key => $value) {
            $rules = \array_map('\\trim', \explode('|', $value));
            foreach ($rules as $rule) $this->examine([$key], $rule);
        }
        return $this;
    }

    public function examine(array $keys, string $rule): self
    {
        if (true !== static::matchRule($rule, $matches)) {
            $message = \sprintf('Wrong Rule [%s] Format Found', $rule);
            static::throwInvalidArgumentException($message);
        }

        if (true !== \method_exists($this, $matches['name'])) {
            $message = \sprintf('Rule Method [%s] Not Found', $matches['name']);
            static::throwRuntimeException($message);
        }

        $args = [];
        if (true === OwoHelperArray::hasSetKey($matches, 'args')) {
            $args = \explode(',', $matches['args']);
        }

        \call_user_func([$this, $matches['name']], $keys, ...$args);
        return $this;
    }

    public function hasSucceed(): bool
    {
        return (true === empty($this->errors));
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function resetErrors(): self
    {
        $this->errors = [];
        return $this;
    }

    public function recapErrors(): string
    {
        $recaps = [];
        foreach ($this->errors as $key => $errors) {
            if (true !== empty($errors)) {
                $recaps[] = static::resumeKeyErrors($key, $errors);
            }
        }
        return \implode(' & ', $recaps);
    }

    protected function saveError(string $key, string $error): self
    {
        $this->prepareErrors($key);
        $this->errors[$key][] = $error;
        return $this;
    }

    protected function prepareErrors(string $key): self
    {
        if (true !== \array_key_exists($key, $this->errors)) {
            $this->errors[$key] = [];
        }
        return $this;
    }

    protected static function matchRule(string $rule, array &$matches = null): bool
    {
        $pattern = static::INPUT_VALIDATOR_RULE_PATTERN;
        return (true === OwoHelperString::matches($pattern, $rule, $matches));
    }

    protected static function resumeKeyErrors(string $key, array $errors): string
    {
        return \sprintf('Key [%s] With Errors [%s]', $key, \implode(', ', $errors));
    }

    public static function realize(array $inputs = [], array $validations = []): self
    {
        return (new static($inputs))->validate($validations);
    }
}
