<?php

namespace framework\libraries\owo\classes\Cores;

use framework\libraries\owo\interfaces\Cores\OwoCorePolicyInterface;


class OwoCorePolicy implements OwoCorePolicyInterface
{
    public function grants(string $action, $actor, $subject, array $args = []): bool
    {
        if (true === $this->proceeds($action)) {
            $grants = \call_user_func([$this, $action], $actor, $subject, $args);
            return (true === $grants);
        }
        return false;
    }

    public function proceeds(string $action): bool
    {
        return (true === \method_exists($this, $action));
    }

    public function getAvailableActions(): array
    {
        $secrets = [ '__construct', 'getAvailableActions', 'grants', 'proceeds', ];
        return \array_filter(\get_class_methods($this), function ($item) use ($secrets) {
            return (true !== \in_array($item, $secrets, true));
        });
    }
}
