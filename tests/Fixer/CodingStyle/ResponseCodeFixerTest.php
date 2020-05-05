<?php

namespace Fixer\CodingStyle;

use JSamson\CS\Fixer\CodingStyle\ResponseCodeFixer;
use PhpCsFixer\Tests\TestCase;
use PhpCsFixer\Tokenizer\Tokens;

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

    /**
     * @dataProvider provideTestApplyFixCases
     */
    public function testApplyFix(Tokens $tokens, array $constsExpected): void
    {
        (new ResponseCodeFixer())->applyFix(new \SplFileInfo('FooController.php'), $tokens);

//        $this->assertFalse(strpos($tokens->generateCode(), '200'));
  //      foreach ($constsExpected as $constExpected) {
    //        $this->assertTrue(false !== strpos($tokens->generateCode(), $constExpected));
      //  }
    }

    public function provideTestApplyFixCases(): \Generator
    {
        yield 'Simple case with 200 instead of Response::HTTP_OK' => [Tokens::fromCode(
            <<<'PHP'
<?php

namespace Foo;

class Bar
{
    /**
     * @Route("/fooBar", name="fooBar", methods={"GET"})
     */
    public function fooBar(): JsonResponse
    {
        return new JsonResponse([], 200);
    }
}
PHP
        ),
            ["Response::HTTP_OK"]
        ];
    }
}