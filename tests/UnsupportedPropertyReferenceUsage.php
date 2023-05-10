<?php

declare(strict_types=1);

namespace Psalm\Tests;

use Psalm\Tests\Traits\InvalidCodeAnalysisTestTrait;

class UnsupportedPropertyReferenceUsage extends TestCase
{
    use InvalidCodeAnalysisTestTrait;

    public function providerInvalidCodeParse(): iterable
    {
        return [
            'instance property' => [
                'code' => <<<'PHP'
                    <?php
                    class A {
                        public int $b = 0;
                    }
                    $a = new A();
                    $b = &$a->b;
                    $b = ''; // Fatal error
                    PHP,
                'error_message' => 'UnsupportedPropertyReferenceUsage',
            ],
            'static property' => [
                'code' => <<<'PHP'
                    <?php
                    class A {
                        public static int $b = 0;
                    }
                    $b = &A::$b;
                    $b = ''; // Fatal error
                    PHP,
                'error_message' => 'UnsupportedPropertyReferenceUsage',
            ],
            'readonly property' => [
                'code' => <<<'PHP'
                    <?php
                    class A {
                        public function __construct(
                            public readonly int $b,
                        ) {
                        }
                    }
                    $a = new A(0);
                    $b = &$a->b;
                    $b = 1; // Fatal error
                    PHP,
                'error_message' => 'UnsupportedPropertyReferenceUsage',
                'error_levels' => [],
                'php_version' => '8.1',
            ],
        ];
    }
}
