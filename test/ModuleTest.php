<?php

declare(strict_types=1);

namespace Sirix\Test\Monolog;

use PHPUnit\Framework\TestCase;
use Sirix\Monolog\Module;

use function is_array;

class ModuleTest extends TestCase
{
    public function testGetConfig()
    {
        $module = new Module();

        $result = $module->getConfig();

        $this->assertTrue(is_array($result));
    }
}
