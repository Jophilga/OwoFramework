<?php

namespace framework\libraries\owo\classes\Cores;

use framework\libraries\owo\classes\Helpers\OwoHelperEncoder;

use framework\libraries\owo\interfaces\Cores\OwoCoreRepositoryInterface;
use framework\libraries\owo\interfaces\Dbases\OwoDbaseConnectionInterface;
use framework\libraries\owo\interfaces\Cores\OwoCoreModelInterface;

use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonCapsulatorTrait;
use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonOccurrenceTrait;
use framework\libraries\owo\traits\Makes\Commons\OwoMakeCommonThrowerTrait;


abstract class OwoCoreModel implements OwoCoreModelInterface
{
    use OwoMakeCommonCapsulatorTrait;
    use OwoMakeCommonOccurrenceTrait;
    use OwoMakeCommonThrowerTrait;

    protected $id = null;

    public function getDTA(): array
    {
        return \array_filter($this->toArray(), function ($item, $key) {
            return (true !== static::hasHidden($key));
        }, \ARRAY_FILTER_USE_BOTH);
    }

    public function getId($default = null): int
    {
        return \intval($this->id ?? $default);
    }

    public function create(): ?self
    {
        return static::createOne($this->toArray());
    }

    public function find(): ?self
    {
        return static::findOne($this->getId());
    }

    public function update(): ?self
    {
        return static::updateOne($this->getId(), $this->toArray());
    }

    public function delete(): ?self
    {
        return static::deleteOne($this->getId());
    }

    public function digest(array $attributes): self
    {
        foreach ($attributes as $attr => $value) $this->set($attr, $value);
        return $this;
    }

    public function alikes(array $matches): bool
    {
        foreach ($matches as $attr => $value) {
            if ($value !== $this->get($attr)) return false;
        }
        return true;
    }

    public function hasNullAttr(string $attr, &$value = null): bool
    {
        return (true === \is_null($value = $this->get($attr)));
    }

    public function set(string $attr, $value): self
    {
        $this->ensureAttr($attr)->$attr = $value;
        return $this;
    }

    public function get(string $attr, $default = null)
    {
        return $this->ensureAttr($attr)->$attr ?? $default;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toJSON(): ?string
    {
        return OwoHelperEncoder::encodeToJson($this, false);
    }

    public function toArray(): array
    {
        return \get_object_vars($this);
    }

    protected function ensureAttr(string $attr): self
    {
        if (true !== \property_exists($this, $attr)) {
            static::throwRuntimeException(\sprintf('Attr [%s] Not Found', $attr));
        }
        return $this;
    }

    protected static function hasHidden(string $attr): bool
    {
        return (true === \in_array($attr, static::getHiddens(), true));
    }

    abstract public static function getHiddens(): array;

    abstract public static function getRepositoryClassName(): string;
}
