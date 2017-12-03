<?php

namespace Tests\Fixer;

use ComposerJsonFixer\Fixer\Fixer;
use PHPUnit\Framework\TestCase;

abstract class AbstractFixerTestCase extends TestCase
{
    /** @var Fixer */
    private $fixer;

    final protected function setUp()
    {
        $reflection = new \ReflectionClass($this);
        $comment    = $reflection->getDocComment();

        if (preg_match('/@covers\s+(\S+)/', $comment, $matches) !== 1) {
            throw new \UnexpectedValueException(sprintf('Test class %s must cover exactly one class.', get_class($this)));
        }

        $fixerClass = $matches[1];

        $this->fixer = new $fixerClass();
    }

    /**
     * @dataProvider provideFixingCases
     *
     * @param array      $expected
     * @param array|null $input
     */
    final public function testFixing(array $expected, array $input = null)
    {
        if ($expected === $input) {
            throw new \InvalidArgumentException('Input parameter must not be equal to expected parameter.');
        }

        if ($input !== null) {
            $fixed = $this->fixer->fix($input);
            $this->assertSame($expected, $fixed);
        }

        $fixed = $this->fixer->fix($expected);
        $this->assertSame($expected, $fixed);
    }

    abstract public function provideFixingCases();

    final public function testFixerIsFinal()
    {
        $reflection = new \ReflectionClass($this->fixer);

        $this->assertTrue($reflection->isFinal());
    }

    final public function testFixerTestIsFinal()
    {
        $reflection = new \ReflectionClass($this);

        $this->assertTrue($reflection->isFinal());
    }
}
