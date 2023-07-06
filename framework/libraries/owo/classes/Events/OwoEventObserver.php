<?php

namespace framework\libraries\owo\classes\Events;

use framework\libraries\owo\interfaces\Events\OwoEventObserverInterface;

use framework\libraries\owo\traits\Takes\Integers\OwoTakeIntegerPriorTrait;
use framework\libraries\owo\traits\Takes\Callables\OwoTakeCallableActionTrait;
use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringNameTrait;
use framework\libraries\owo\traits\Takes\Strings\OwoTakeStringEventTrait;


class OwoEventObserver implements OwoEventObserverInterface
{
    use OwoTakeIntegerPriorTrait;
    use OwoTakeCallableActionTrait;
    use OwoTakeStringNameTrait;
    use OwoTakeStringEventTrait;

    public function __construct(string $event, callable $action, int $prior = 0)
    {
        $this->setEvent($event)->setAction($action)->setPrior($prior);
    }
}
