<?php

declare(strict_types=1);

namespace Alchemy\Zippy\Tests\Adapter\VersionProbe;

use Alchemy\Zippy\Tests\TestCase;
use Alchemy\Zippy\Adapter\VersionProbe\BSDTarVersionProbe;
use Alchemy\Zippy\Adapter\VersionProbe\VersionProbeInterface;

abstract class AbstractTarVersionProbeTest extends TestCase
{
    public function testGetStatusIsOk()
    {
        $mockInflator = $this->getBuilder($this->getCorrespondingVersionOutput());
        $mockDeflator = $this->getBuilder($this->getCorrespondingVersionOutput());

        $classname = $this->getProbeClassName();

        $probe = new $classname($this->getMockedProcessBuilderFactory($mockInflator), $this->getMockedProcessBuilderFactory($mockDeflator));

        $this->assertEquals(VersionProbeInterface::PROBE_OK, $probe->getStatus());
        // second time is served from cache
        $this->assertEquals(VersionProbeInterface::PROBE_OK, $probe->getStatus());
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('provideInvalidVersions')]
    public function testGetStatusIsNotOk($inflatorVersion, $deflatorVersion, $inflatorCall, $deflatorCall)
    {
        $mockInflatorBuilder = $inflatorVersion ? $this->getBuilder($inflatorVersion, $inflatorCall) : null;
        $mockDeflatorBuilder = $deflatorVersion ? $this->getBuilder($deflatorVersion, $deflatorCall) : null;

        $builderInflator = $mockInflatorBuilder ? $this->getMockedProcessBuilderFactory($mockInflatorBuilder, $inflatorCall ? 1 : 0) : null;
        $builderDeflator = $mockDeflatorBuilder ? $this->getMockedProcessBuilderFactory($mockDeflatorBuilder, $deflatorCall ? 1 : 0) : null;

        $classname = $this->getProbeClassName();

        $probe = new $classname($builderInflator, $builderDeflator);

        $this->assertEquals(VersionProbeInterface::PROBE_NOTSUPPORTED, $probe->getStatus());
        // second time is served from cache
        $this->assertEquals(VersionProbeInterface::PROBE_NOTSUPPORTED, $probe->getStatus());
    }

    public function provideInvalidVersions(): \Iterator
    {
        yield array($this->getCorrespondingVersionOutput(), $this->getNonCorrespondingVersionOutput(), true, true);
        yield array($this->getNonCorrespondingVersionOutput(), $this->getCorrespondingVersionOutput(), true, false);
    }

    protected function getBuilder($version, $call = true)
    {
        $mock = $this->createMock('\Alchemy\Zippy\ProcessBuilder\ProcessBuilder');

        $mockBuilder = $mock
            ->expects($call ? $this->once() : $this->never())
            ->method('add');
        if ($call) {
            $mockBuilder->with('--version');
        }
        $mockBuilder->willReturnSelf();

        $process = $this->getSuccessFullMockProcess($call ? 1 : 0);

        $mock
            ->expects($call ? $this->once() : $this->never())
            ->method('getProcess')
            ->willReturn($process);

        $process
            ->expects($call ? $this->once() : $this->never())
            ->method('getOutput')
            ->willReturn($version);

        return $mock;
    }

    protected function getBSDTarVersionOutput()
    {
        return 'bsdtar 2.8.3 - libarchive 2.8.3';
    }

    protected function getGNUTarVersionOutput()
    {
        return 'tar (GNU tar) 1.17
Copyright (C) 2007 Free Software Foundation, Inc.
License GPLv2+: GNU GPL version 2 or later <http://gnu.org/licenses/gpl.html>
This is free software: you are free to change and redistribute it.
There is NO WARRANTY, to the extent permitted by law.

Modified to support extended attributes.
Written by John Gilmore and Jay Fenlason.';
    }

    abstract public function getProbeClassName();
    abstract public function getCorrespondingVersionOutput();
    abstract public function getNonCorrespondingVersionOutput();
}
