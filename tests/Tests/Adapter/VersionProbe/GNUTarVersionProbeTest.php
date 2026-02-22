<?php

declare(strict_types=1);

namespace Alchemy\Zippy\Tests\Adapter\VersionProbe;

final class GNUTarVersionProbeTest extends AbstractTarVersionProbeTest
{
    public function getProbeClassName()
    {
        return 'Alchemy\Zippy\Adapter\VersionProbe\GNUTarVersionProbe';
    }

    public function getCorrespondingVersionOutput()
    {
        return $this->getGNUTarVersionOutput();
    }

    public function getNonCorrespondingVersionOutput()
    {
        return $this->getBSDTarVersionOutput();
    }
}
