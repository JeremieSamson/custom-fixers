<?php

namespace JSamson\CS\Fixer\Deprecation;

use JSamson\CS\Fixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

final class MethodToRouteAnnotationFixer extends AbstractFixer
{
    public function getDefinition(): FixerDefinition
    {
        return new FixerDefinition(
            'Replace @Method annotation by @Route(..., methods={})',
            [
                new CodeSample(
                    '<?php 
/**
 * @Route("/hello-world", name="hello_world")
 * @Method("GET")
 */
public function helloWorldAction(){
//...
}                          
'
                ),
            ]
        );
    }

    public function isRisky(): bool
    {
        return false;
    }

    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isAllTokenKindsFound([\T_DOC_COMMENT]);
    }

    public function supports(\SplFileInfo $file): bool
    {
        return preg_match('/Controller$/', $file->getBasename('.php'));
    }

    public function applyFix(\SplFileInfo $file, Tokens $tokens): void
    {
        /** @var Token $token */
        foreach ($tokens as $index => $token) {
            if (!$token->isGivenKind([\T_DOC_COMMENT])) {
                continue;
            }

            if ($token->isGivenKind(\T_DOC_COMMENT)
                && false !== strpos($token->getContent(), '@Route')
                && false !== strpos($token->getContent(), '@Method')
            ) {
                $token->setContent($this->replaceMethodToRouteAnnotation($token->getContent()));
            }
        }
    }

    private function replaceMethodToRouteAnnotation(string $content): string
    {
        $methodAnnotationStart = strpos($content, '@Method');
        $methodAnnotationEnd = strpos($content, ')', $methodAnnotationStart) + 1;
        $methodsToMove = $this->getMethodsToMove(substr(
            $content,
            $methodAnnotationStart,
            $methodAnnotationEnd - $methodAnnotationStart
        ));
        $content =  $this->removeMethodLine($content, $methodAnnotationEnd);
        $routeAnnotationIndexes = [];
        $offset = 0;

        for($i=0 ; $i<substr_count($content, '@Route') ; $i++) {
            $routeAnnotationStart = strpos($content, '@Route', $offset);
            $offset += $routeAnnotationStart + 1;

            $content = $this->addMethodArgInRoute(
                $content,
                $methodsToMove,
                $routeAnnotationStart
            );
        }

        foreach ($routeAnnotationIndexes as $routeAnnotationStart) {
            $content = $this->addMethodArgInRoute(
                $content,
                $methodsToMove,
                $routeAnnotationStart
            );
        }

        return $content;
    }

    private function addMethodArgInRoute(string $content, array $methodsToMove, int $routeAnnotationStart): string
    {
        $openingBraceOfRoute = strpos($content, '(', $routeAnnotationStart);

        if (false !== $openingBraceOfFunction = strpos($content, 'condition', $openingBraceOfRoute + 1)) {
            $closingBraceOfFunction = strpos($content, '"', $openingBraceOfFunction + \strlen('condition=') + 1);
            $routeAnnotationEnd = strpos($content, ')', $closingBraceOfFunction);
        } else {
            $routeAnnotationEnd = strpos($content, ')', $routeAnnotationStart);
        }

        $routeLine = substr($content, $routeAnnotationStart, $routeAnnotationEnd - $routeAnnotationStart+1);

        $newRouteLine = substr_count($routeLine, PHP_EOL) >= 1
            ? $this->addMethodArgInMultiLineRoute($routeLine, $methodsToMove)
            : $newRouteLine = $this->addMethodArgInSingleLineRoute($routeLine, $methodsToMove)
        ;

        return str_replace($routeLine, $newRouteLine, $content);
    }

    private function addMethodArgInMultiLineRoute(string $routeLine, array $methodsToMove): string
    {
        $routeLine = str_replace('*', '', $routeLine);
        $routeLine = str_replace(PHP_EOL, '', $routeLine);
        $routeLine = str_replace(' ', '', $routeLine);
        $args = explode(',', substr($routeLine, $start = strpos($routeLine, '(')+1, strpos($routeLine, ')') - $start));

        $routeCleaned = '@Route('.PHP_EOL;

        foreach ($args as $arg) {
            $routeCleaned .= '     *     '.$arg.','.PHP_EOL;
        }
        $routeCleaned .= '     *     methods={'.implode(',', $methodsToMove).'},'.PHP_EOL;
        $routeCleaned .= '     * )';

        return $routeCleaned;
    }

    private function addMethodArgInSingleLineRoute(string $routeLine, array $methodsToMove): string
    {

        return str_replace(
            $routeLine = str_replace(')', '', $routeLine),
            $routeLine.', methods={'.implode(', ', $methodsToMove).'})', $routeLine
        );
    }

    private function removeMethodLine(string $content, int $methodAnnotationEnd): string
    {
        $newLineIndexes = $this->getNewLineIndexes($content);
        $startIndexToRemove = $newLineIndexes[array_search($methodAnnotationEnd, $newLineIndexes) - 1] + 1;
        $endIndexToRemove = $methodAnnotationEnd + \strlen(PHP_EOL);
        $lineToRemove = substr($content, $startIndexToRemove, $endIndexToRemove - $startIndexToRemove);

        return str_replace($lineToRemove, '', $content);
    }

    private function getNewLineIndexes(string $content): array
    {
        $lastPos = 0;
        $indexes = [];

        while (false !== ($lastPos = strpos($content, PHP_EOL, $lastPos))) {
            $indexes[] = $lastPos;
            $lastPos = $lastPos + \strlen(PHP_EOL);
        }

        return $indexes;
    }

    private function getMethodsToMove(string $methodLine): array
    {
        $methodLine = str_replace('|', ',', $methodLine);
        $methodLine = str_replace('"', '', $methodLine);
        $methodLine = str_replace('\'', '', $methodLine);
        $methodLine = str_replace('{', '', $methodLine);
        $methodLine = str_replace('}', '', $methodLine);
        $parenthesisStart = strpos($methodLine, '(') + 1;
        $parenthesisEnd = strpos($methodLine, ')');
        $methodArgs = substr($methodLine, $parenthesisStart, $parenthesisEnd - $parenthesisStart);
        $methodArgs = explode(',', $methodArgs);

        foreach ($methodArgs as $key => $arg) {
            $methodArgs[$key] = '"'.trim($arg).'"';
        }

        return $methodArgs;
    }
}
