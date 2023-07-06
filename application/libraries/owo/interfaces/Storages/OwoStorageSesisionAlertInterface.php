<?php

namespace application\libraries\owo\interfaces\Storages;

use application\models\owo\Alert;

use application\libraries\owo\interfaces\Storages\OwoStorageSessionInterface;


interface OwoStorageSesisionAlertInterface extends OwoStorageSessionInterface
{
    public function useLastUnseenAlert(): ?Alert;

    public function getLastUnseenAlert($default = null): ?Alert;

    public function addUnseenAlert(Alert $alert): self;

    public function deleteUnseenAlerts(): self;

    public function deleteUnseenAlert($id): self;

    public function hasUnseenAlert($id = null): bool;

    public function getUnseenAlert($id, $default = null): ?Alert;

    public function getUnseenAlerts(): array;

    public function getLastSeenAlert($default = null): ?Alert;

    public function addSeenAlert(Alert $alert): self;

    public function deleteSeenAlerts(): self;

    public function deleteSeenAlert($id): self;

    public function hasSeenAlert($id = null): bool;

    public function getSeenAlert($id, $default = null): ?Alert;

    public function getSeenAlerts(): array;
}
