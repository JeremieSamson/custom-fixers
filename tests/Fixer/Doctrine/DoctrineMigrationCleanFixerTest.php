<?php

namespace Fixer\Doctrine;

use JSamson\CS\Fixer\Doctrine\DoctrineMigrationCleanFixer;
use PhpCsFixer\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class DoctrineMigrationCleanFixerTest extends TestCase
{
    /**
     * @dataProvider provideTestSupportsCases
     */
    public function testSupports(string $fileName, bool $expected): void
    {
        $this->assertEquals($expected, (new DoctrineMigrationCleanFixer())->supports(new \SplFileInfo($fileName)));
    }

    public function provideTestSupportsCases(): \Generator
    {
        yield ['Version1234.php', false];
        yield ['1234Version.php', false];
        yield ['Version12345678901234.php', true];
        yield ['Version123456789012.php', false];
        yield ['Version20200101010101.php', true];
    }
}
