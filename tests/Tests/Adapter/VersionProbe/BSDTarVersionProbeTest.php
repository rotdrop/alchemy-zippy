<?php

declare(strict_types=1);

namespace Alchemy\Zippy\Tests\Adapter\VersionProbe;

final class BSDTarVersionProbeTest extends AbstractTarVersionProbeTest
{
    public function getProbeClassName()
    {
        return 'Alchemy\Zippy\Adapter\VersionProbe\BSDTarVersionProbe';
    }

    public function getCorrespondingVersionOutput()
    {
        return $this->getBSDTarVersionOutput();
    }

    public function getNonCorrespondingVersionOutput()
    {
        return $this->getGNUTarVersionOutput();
    }
}
