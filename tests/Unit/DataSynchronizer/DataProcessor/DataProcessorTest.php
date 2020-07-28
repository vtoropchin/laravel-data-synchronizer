<?php declare(strict_types=1);

namespace Tests\Unit\DataSynchronizer\DataProcessor;

use App\DataSynchronizer\DataProcessor\AbstractDataProcessor;
use PHPUnit\Framework\TestCase;

/**
 * Class DataProcessorTest
 * @package Tests\Unit\DataSynchronizer\AbstractDataProcessor
 */
class DataProcessorTest extends TestCase
{
    /**
     * @var AbstractDataProcessor
     */
    protected $abstractDataProcessor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->abstractDataProcessor = new class extends AbstractDataProcessor {
        };
    }

    /**
     * @return array|array[]
     */
    public function loadDataDataProvider(): array
    {
        return [
            [
                'entityClass'       => Entity::class,
                'expectedSuccess'   => true,
                'expectedException' => false,
            ],
            [
                'entityClass'       => NotEntity::class,
                'expectedSuccess'   => false,
                'expectedException' => true,
            ],
            [
                'entityClass'       => 'UnknownClass',
                'expectedSuccess'   => false,
                'expectedException' => true,
            ],
        ];
    }

    /**
     * @dataProvider loadDataDataProvider
     *
     * @param string $entityClass
     * @param bool   $expectedSuccess
     * @param bool   $expectedException
     *
     * @throws \App\Exceptions\SystemException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function testLoadData(string $entityClass, bool $expectedSuccess, bool $expectedException): void
    {
        if ($expectedException) {
            $this->expectException(\Throwable::class);
        }

        $result = $this->abstractDataProcessor->loadData($entityClass);

        if ($expectedSuccess) {
            self::assertSame(0, $result->getCurrentIndex());
            self::assertInstanceOf(Entity::class, $result->getCurrentItem());
        }
    }


    /**
     * @return array|bool[][]
     */
    public function getCurrentItemDataProvider(): array
    {
        return [
            [
                'entityClass' => Entity::class,
                'loadData'    => true,
            ],
            [
                'entityClass' => Entity::class,
                'loadData'    => false,
            ],
        ];
    }

    /**
     * @dataProvider getCurrentItemDataProvider
     *
     * @param string $entityClass
     * @param bool   $loadData
     *
     * @throws \App\Exceptions\SystemException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function testGetCurrentItem(string $entityClass, bool $loadData): void
    {
        if ($loadData) {
            $this->abstractDataProcessor->loadData($entityClass);
        } else {
            $this->expectException(\Throwable::class);
        }

        $result = $this->abstractDataProcessor->getCurrentItem();

        self::assertInstanceOf(Entity::class, $result);
    }

    /**
     * @return array|bool[][]
     */
    public function getCurrentIndexDataProvider(): array
    {
        return [
            [
                'entityClass' => Entity::class,
                'loadData'    => true,
            ],
            [
                'entityClass' => Entity::class,
                'loadData'    => false,
            ],
        ];
    }

    /**
     * @dataProvider getCurrentIndexDataProvider
     *
     * @param string $entityClass
     * @param bool   $loadData
     *
     * @throws \App\Exceptions\SystemException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function testGetCurrentIndex(string $entityClass, bool $loadData): void
    {
        if ($loadData) {
            $this->abstractDataProcessor->loadData($entityClass);
        } else {
            $this->expectException(\Throwable::class);
        }

        $currentIndex = $this->abstractDataProcessor->getCurrentIndex();

        self::assertSame(0, $currentIndex);
    }

    public function testIsREadAll(): void
    {
        $result = $this->abstractDataProcessor->isReadAll();

        self::assertIsBool($result);
    }

}
