<?php

namespace Fixer\Deprecation;

use JSamson\CS\Fixer\Deprecation\MethodToRouteAnnotationFixer;
use PhpCsFixer\Tests\TestCase;
use PhpCsFixer\Tokenizer\Tokens;

class MethodToRouteAnnotationFixerTest extends TestCase
{
    /**
     * @dataProvider provideTestSupportsCases
     */
    public function testSupports(string $fileName, bool $expected): void
    {
        $this->assertEquals($expected, (new MethodToRouteAnnotationFixer())->supports(new \SplFileInfo($fileName)));
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
    public function testApplyFix(Tokens $tokens, array $routesArgsExpected): void
    {
        (new MethodToRouteAnnotationFixer())->applyFix(new \SplFileInfo('FooController.php'), $tokens);

        $this->assertFalse(strpos($tokens->generateCode(), '@Method'));
        foreach ($routesArgsExpected as $routeArgsExpected) {
            //dd($tokens->generateCode());
            $this->assertTrue(false !== strpos($tokens->generateCode(), $routeArgsExpected));
        }
    }

    public function provideTestApplyFixCases(): \Generator
    {
        yield 'Simple case with route and annotation with one method' =>[Tokens::fromCode(
            <<<'PHP'
<?php

namespace Foo;

class Bar
{
    /**
     * @Route("/fooBar", name="fooBar")
     * @Method("GET")
     */
    public function fooBar()
    {

    }
}
PHP
        ),
            ['@Route("/fooBar", name="fooBar", methods={"GET"})']
        ];

        yield 'Simple case with route and annotation with multiples methods' =>[Tokens::fromCode(
            <<<'PHP'
<?php

namespace Foo;

class Bar
{
    /**
     * @Route("/fooBar", name="fooBar")
     * @Method("GET", "POST")
     */
    public function fooBar()
    {

    }
}
PHP
        ),
            ['@Route("/fooBar", name="fooBar", methods={"GET", "POST"})']
        ];

        yield 'Simple case with route with option arg and annotation with one method' =>[Tokens::fromCode(
            <<<'PHP'
<?php

namespace Foo;

class Bar
{
    /**
     * @Route("/fooBar", name="fooBar", options={"expose"=true})
     * @Method("GET")
     */
    public function fooBar()
    {

    }
}
PHP
        ),
            ['@Route("/fooBar", name="fooBar", options={"expose"=true}, methods={"GET"})']
        ];

        yield 'Simple case with route with option arg and annotation with multiple methods' =>[Tokens::fromCode(
            <<<'PHP'
<?php

namespace Foo;

class Bar
{
    /**
     * @Route("/fooBar", name="fooBar", options={"expose"=true})
     * @Method("GET", "POST")
     */
    public function fooBar()
    {

    }
}
PHP
        ),
            ['@Route("/fooBar", name="fooBar", options={"expose"=true}, methods={"GET", "POST"})']
        ];

        yield 'Case with mulitple routes and annotation with one method' =>[Tokens::fromCode(
            <<<'PHP'
<?php

namespace Foo;

class Bar
{
    /**
     * @Route("/fooBar", name="fooBar")
     * @Route("/{locale}/fooBar", name="fooBar_locale")
     * @Method("GET")
     */
    public function fooBar()
    {

    }
}
PHP
        ),
            [
                '@Route("/fooBar", name="fooBar", methods={"GET"})',
                '@Route("/{locale}/fooBar", name="fooBar_locale", methods={"GET"})',
            ]
        ];

        yield 'Case with mulitple routes on multiple lines and annotation with one method' =>[Tokens::fromCode(
            <<<'PHP'
<?php

namespace Foo;

class Bar
{
    /**
     * @Route("/fooBar", 
     *          name="fooBar",
     *          options={"expose"=true}, requirements={"_locale" = "%allowed_languages%"})     
     * @Route("/{locale}/fooBar",
     *          name="fooBar_locale",
     *          options={"expose"=true}, requirements={"_locale" = "%allowed_languages%"})
     * @Method("GET")
     */   
    public function fooBar()
    {
    
    }
}
PHP
        ),
            [
                <<<'PHP'
     * @Route(
     *     "/fooBar",
     *     name="fooBar",
     *     options={"expose"=true},
     *     requirements={"_locale"="%allowed_languages%"},
     *     methods={"GET"},
     * )
PHP,
                <<<'PHP'
     * @Route(
     *     "/{locale}/fooBar",
     *     name="fooBar_locale",
     *     options={"expose"=true},
     *     requirements={"_locale"="%allowed_languages%"},
     *     methods={"GET"},
     * )
PHP
            ]
        ];
    }
}