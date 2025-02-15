<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog\Unit;

use Sirix\Monolog\Module;
use Codeception\Test\Unit;

use function is_array;

class ModuleTest extends Unit
{
    public function testGetConfig()
    {
        $module = new Module();

        $result = $module->getConfig();

        $this->assertTrue(is_array($result));
    }
}
