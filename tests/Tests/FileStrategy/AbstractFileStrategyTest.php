<?php

declare(strict_types=1);

namespace Alchemy\Zippy\Tests\FileStrategy;

use Alchemy\Zippy\Adapter\AdapterContainer;
use Alchemy\Zippy\Tests\TestCase;
use Alchemy\Zippy\Exception\RuntimeException;

final class AbstractFileStrategyTest extends TestCase
{
    /**
     * @expectedException   \InvalidArgumentException
     */
    public function testGetAdaptersWithNoDefinedServices()
    {
        $container = AdapterContainer::load();

        $stub = $this->getMockForAbstractClass('Alchemy\Zippy\FileStrategy\AbstractFileStrategy', array($container));
        $stub
            ->method('getServiceNames')
            ->willReturn(array(
                'Unknown\Services'
            ));


        $adapters = $stub->getAdapters();
        $this->assertInternalType('array', $adapters);
        $this->assertCount(0, $adapters);
    }

    public function testGetAdapters()
    {
        $container = AdapterContainer::load();

        $stub = $this->getMockForAbstractClass('Alchemy\Zippy\FileStrategy\AbstractFileStrategy', array($container));
        $stub
            ->method('getServiceNames')
            ->willReturn(array(
                'Alchemy\\Zippy\\Adapter\\ZipAdapter',
                'Alchemy\\Zippy\\Adapter\\ZipExtensionAdapter'
            ));

        $adapters = $stub->getAdapters();
        $this->assertInternalType('array', $adapters);
        $this->assertCount(2, $adapters);
        $this->assertContainsOnlyInstancesOf('Alchemy\\Zippy\\Adapter\\AdapterInterface', $adapters);
    }

    public function testGetAdaptersWithAdapterThatRaiseAnException()
    {
        $adapterMock = $this->createStub('\Alchemy\Zippy\Adapter\AdapterInterface');
        $container = $this->createMock('\Alchemy\Zippy\Adapter\AdapterContainer');
        $container
            ->expects($this->never())
            ->method('offsetGet')
            ->with('Alchemy\\Zippy\\Adapter\\ZipAdapter')
            ->willReturn($adapterMock);

        $container
            ->expects($this->once())
            ->method('offsetGet')
            ->with('Alchemy\\Zippy\\Adapter\\ZipExtensionAdapter')
            ->willThrowException(new RuntimeException());

        $stub = $this->getMockForAbstractClass('Alchemy\Zippy\FileStrategy\AbstractFileStrategy', array($container));
        $stub
            ->method('getServiceNames')
            ->willReturn(array(
                'Alchemy\\Zippy\\Adapter\\ZipAdapter',
                'Alchemy\\Zippy\\Adapter\\ZipExtensionAdapter'
            ));

        $adapters = $stub->getAdapters();
        $this->assertInternalType('array', $adapters);
        $this->assertCount(1, $adapters);
        foreach ($adapters as $adapter) {
            $this->assertSame($adapterMock, $adapter);
        }
    }   
}
