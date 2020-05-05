<?php

namespace JSamson\CS\Fixer\CodingStyle;

use JSamson\CS\Fixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

final class ResponseCodeFixer extends AbstractFixer
{
    private const CONCERNED_CLASSES = ["Response", "JsonResponse"];

    public function getDefinition(): FixerDefinition
    {
        return new FixerDefinition(
            'Replace int wrote in Response to const from Response class in HttpFoundation Symfony component',
            [
                new CodeSample(
                    '<?php 
/**
 * @Route("/hello-world", name="hello_world", methods={"GET"})
 */
public function index(): JsonResponse
{
    return new JsonResponse([], 200);
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
        return $tokens->isAllTokenKindsFound([\T_RETURN]);
    }

    public function supports(\SplFileInfo $file): bool
    {
        return preg_match('/Controller$/', $file->getBasename('.php'));
    }

    public function applyFix(\SplFileInfo $file, Tokens $tokens): void
    {
        /** @var Token $token */
        foreach ($tokens as $index => $token) {
            if (!$token->isGivenKind([\T_RETURN])) {
                continue;
            }

            if ($token->isGivenKind(\T_RETURN) && in_array($tokens[$index+4]->getContent(), self::CONCERNED_CLASSES)) {
                $currentIndex = $index;
                $endOfReturn = $tokens->getNextTokenOfKind($index, [';']);
var_dump($currentIndex, $endOfReturn);
                for($j=$currentIndex ; $j<=$endOfReturn ; $j++) {
                    var_dump($j, $tokens[$j]->getName());
                    if (\T_LNUMBER === constant($tokens[$j]->getName())) {
                        dd("ici");
                    }
                }

                die;
            }
        }
    }
}
