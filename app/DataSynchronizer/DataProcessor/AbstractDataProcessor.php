<?php declare(strict_types=1);


namespace App\DataSynchronizer\DataProcessor;


use App\DataSynchronizer\Entities\AbstractEntity;
use App\Exceptions\SystemException;

/**
 * Class AbstractDataProcessor
 * @package App\DataSynchronizer\AbstractDataProcessor]
 */
abstract class AbstractDataProcessor
{
    /**
     * @var AbstractEntity[]
     */
    protected $entities;

    protected $isReadAll = false;

    protected $currentIndex;

    /**
     * @param string $entityClass
     *
     * @return $this
     * @throws SystemException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function loadData(string $entityClass): AbstractDataProcessor
    {
        $entity = app()->make($entityClass);

        if (!($entity instanceof AbstractEntity)) {
            throw new SystemException('An Entity class expected');
        }

        $this->entities = [$entity];

        $this->setCurrentIndex(0);

        return $this;
    }

    /**
     * @return AbstractEntity
     * @throws SystemException
     */
    public function getCurrentItem(): AbstractEntity
    {
        if ($this->currentIndex === null) {
            throw new SystemException('You must load data before getting an current item');
        }

        $entity = $this->entities[$this->currentIndex];

        $this->updateReadPosition();

        return $entity;
    }

    /**
     * @return int
     * @throws SystemException
     */
    public function getCurrentIndex(): int
    {
        if ($this->currentIndex === null) {
            throw new SystemException('You must load data before getting an current item');
        }

        return $this->currentIndex;
    }

    /**
     * @return bool
     */
    public function isReadAll(): bool
    {
        return $this->isReadAll;
    }

    /**
     * @return $this
     */
    protected function updateReadPosition(): AbstractDataProcessor
    {
        if ((count($this->entities) - 1) === $this->currentIndex) {
            $this->setIsReadAll(true);
        } else {
            $this->setCurrentIndex($this->currentIndex + 1);
        }

        return $this;
    }

    /**
     * @param int $index
     */
    protected function setCurrentIndex(int $index): void
    {
        $this->currentIndex = $index;
    }

    /**
     * @param bool $isReadAll
     */
    protected function setIsReadAll(bool $isReadAll): void
    {
        $this->isReadAll = $isReadAll;
    }
}
