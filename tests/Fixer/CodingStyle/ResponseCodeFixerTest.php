<?php

namespace Fixer\CodingStyle;

use JSamson\CS\Fixer\CodingStyle\ResponseCodeFixer;
use PhpCsFixer\Tests\TestCase;

class ResponseCodeFixerTest extends TestCase
{
    /**
     * @dataProvider provideTestSupportsCases
     */
    public function testSupports(string $fileName, bool $expected): void
    {
        $this->assertEquals($expected, (new ResponseCodeFixer())->supports(new \SplFileInfo($fileName)));
    }

    public function provideTestSupportsCases(): \Generator
    {
        yield ['ControllerFoo.php', false];
        yield ['FooController.php', true];
        yield ['FooTest.php', false];
        yield ['TestFoo.php', false];
        yield ['FooControllerBar.php', false];
        yield ['BarControllerFoo.php', false];
        yield ['FooBarController.php', true];
    }
}