<?php
/**
 * @data 05.06.2020
 * @package Портал ТФОМС
 * @subpackage Модуль - ОТчеты
 * @author <yurinskiy@krasmed.ru>
 */

namespace Core\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks
 */

trait EntityDatableTrait
{
    /** @ORM\Column(name="created", type="datetime") */
    private DateTime $created;

    /** @ORM\Column(name="updated", type="datetime", nullable=true) */
    private ?DateTime $updated;

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist(): void
    {
        $this->created = new DateTime('NOW');
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate(): void
    {
        $this->updated = new DateTime('NOW');
    }

    public function getCreated(): DateTime
    {
        return $this->created;
    }

    public function getUpdated(): ?DateTime
    {
        return $this->updated;
    }
}