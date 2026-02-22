<?php

declare(strict_types=1);

namespace Alchemy\Zippy\Tests\FileStrategy;

use Alchemy\Zippy\FileStrategy\ZipFileStrategy;

final class ZipFileStrategyTest extends FileStrategyTestCase
{
    protected function getStrategy($container)
    {
        return new ZipFileStrategy($container);
    }
}
