<?php

declare(strict_types=1);

namespace Alchemy\Zippy\Tests\FileStrategy;

use Alchemy\Zippy\FileStrategy\TarFileStrategy;

final class TarFileStrategyTest extends FileStrategyTestCase
{
    protected function getStrategy($container)
    {
        return new TarFileStrategy($container);
    }
}
