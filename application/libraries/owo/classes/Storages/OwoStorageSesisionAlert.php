<?php

namespace application\libraries\owo\classes\Storages;

use framework\libraries\owo\classes\Helpers\OwoHelperArray;
use application\libraries\owo\classes\Storages\OwoStorageSession;
use application\models\owo\Alert;

use application\libraries\owo\interfaces\Storages\OwoStorageSesisionAlertInterface;


class OwoStorageSesisionAlert extends OwoStorageSession implements OwoStorageSesisionAlertInterface
{
    public function __construct(OwoSessionPrefixInterface $session)
    {
        parent::__construct($session);
    }

    public function useLastUnseenAlert(): ?Alert
    {
        if (true !== \is_null($alert = $this->getLastUnseenAlert())) {
            if (true !== \is_null($id = $alert->getId())) {
                $this->addSeenAlert($alert)->deleteUnseenAlert($id);
                return $alert;
            }
        }
        return null;
    }

    public function getLastUnseenAlert($default = null): ?Alert
    {
        $alerts = $this->getUnseenAlerts();
        return \end($alerts) ?: $default;
    }

    public function addUnseenAlert(Alert $alert): self
    {
        if (true !== \is_null($id = $alert->getId())) {
            return $this->session->save($this->prepareUnseenAlertKey($id), $alert);
        }
        return $this;
    }

    public function deleteUnseenAlerts(): self
    {
        foreach ($this->getUnseenAlerts() as $alert) {
            if (true !== \is_null($id = $alert->getId())) {
                $this->deleteUnseenAlert($id);
            }
        }
        return $this;
    }

    public function deleteUnseenAlert($id): self
    {
        $this->session->remove($this->prepareUnseenAlertKey($id));
        return $this;
    }

    public function hasUnseenAlert($id = null): bool
    {
        if (true !== \is_null($id)) {
            return (true === $this->session->has($this->prepareUnseenAlertKey($id)));
        }
        return (true !== empty($this->getUnseenAlerts()));
    }

    public function getUnseenAlert($id, $default = null): ?Alert
    {
        return $this->session->get($this->prepareUnseenAlertKey($id), $default);
    }

    public function getUnseenAlerts(): array
    {
        $search = \sprintf('%s.unseen.', $this->session->getPrefix());
        $results = OwoHelperArray::filterByPrefixKey($this->session->all(), $search);
        return $results;
    }

    public function getLastSeenAlert($default = null): ?Alert
    {
        $alerts = $this->getSeenAlerts();
        return \end($alerts) ?: $default;
    }

    public function addSeenAlert(Alert $alert): self
    {
        if (true !== \is_null($id = $alert->getId())) {
            return $this->session->save($this->prepareSeenAlertKey($id), $alert);
        }
        return $this;
    }

    public function deleteSeenAlerts(): self
    {
        foreach ($this->getSeenAlerts() as $alert) {
            if (true !== \is_null($id = $alert->getId())) {
                $this->deleteSeenAlert($id);
            }
        }
        return $this;
    }

    public function deleteSeenAlert($id): self
    {
        $this->session->remove($this->prepareSeenAlertKey($id));
        return $this;
    }

    public function hasSeenAlert($id = null): bool
    {
        if (true !== \is_null($id)) {
            return (true === $this->session->has($this->prepareSeenAlertKey($id)));
        }
        return (true !== empty($this->getSeenAlerts()));
    }

    public function getSeenAlert($id, $default = null): ?Alert
    {
        return $this->session->get($this->prepareSeenAlertKey($id), $default);
    }

    public function getSeenAlerts(): array
    {
        $search = \sprintf('%s.seen.', $this->session->getPrefix());
        $results = OwoHelperArray::filterByPrefixKey($this->session->all(), $search);
        return $results;
    }

    protected function prepareSeenAlertKey($key): string
    {
        return \sprintf('seen.%s', \strval($key));
    }

    protected function prepareUnseenAlertKey($key): string
    {
        return \sprintf('unseen.%s', \strval($key));
    }
}
