<?php

namespace Fixer\Deprecation;

use JSamson\CS\Fixer\Deprecation\SensioToSymfonyRouteFixer;
use PhpCsFixer\Tests\TestCase;
use PhpCsFixer\Tokenizer\Tokens;

class SensioToSymfonyRouteFixerTest extends TestCase
{
    /**
     * @dataProvider provideTestSupportsCases
     */
    public function testSupports(string $fileName, bool $expected): void
    {
        $this->assertEquals($expected, (new SensioToSymfonyRouteFixer())->supports(new \SplFileInfo($fileName)));
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

    /**
     * @dataProvider provideTestApplyFixCases
     */
    public function testApplyFix(Tokens $tokens): void
    {
        (new SensioToSymfonyRouteFixer())->applyFix(new \SplFileInfo('FooController.php'), $tokens);

        $this->assertFalse(strpos($tokens->generateCode(), 'use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;'));
        $this->assertTrue(false !== strpos($tokens->generateCode(), 'use Symfony\Component\Routing\Annotation\Route;'));
    }

    public function provideTestApplyFixCases(): \Generator
    {
        yield 'Test when use is in the middle' =>[Tokens::fromCode(
<<<'PHP'
<?php

namespace Foo\Bar;

use Foo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Foo\Bar;

class Foo
{

}
PHP
        )];

        yield 'Test when use is first' =>[Tokens::fromCode(
            <<<'PHP'
<?php

namespace Foo\Bar;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Foo\Bar;

class Foo
{

}
PHP
        )];

        yield 'Test when use is last' => [Tokens::fromCode(
            <<<'PHP'
<?php

namespace Foo\Bar;

use Foo\Bar;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class Foo
{

}
PHP
        )];

        yield 'Test when use is alone' => [Tokens::fromCode(
            <<<'PHP'
<?php

namespace Foo\Bar;

use Foo\Bar;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class Foo
{

}
PHP
        )];
    }
}