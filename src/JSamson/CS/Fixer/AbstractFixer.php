<?php

namespace JSamson\CS\Fixer;

abstract class AbstractFixer extends \PhpCsFixer\AbstractFixer
{
    public function getName()
    {
        return 'Jsamson/'.parent::getName();
    }
}