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

        if (\preg_match('/@covers\s+(\S+)/', $comment, $matches) !== 1) {
            throw new \UnexpectedValueException(\sprintf('Test %s must cover exactly one class.', \get_class($this)));
        }

        $fixerClass = $matches[1];

        $this->fixer = new $fixerClass();
    }

    final public function testFixerIsFinal()
    {
        $fixerReflection = new \ReflectionClass($this->fixer);

        $this->assertTrue($fixerReflection->isFinal());
    }

    final public function testFixerImplementsFixer()
    {
        $this->assertInstanceOf(Fixer::class, $this->fixer);
    }

    final public function testFixerTestIsFinal()
    {
        $reflection = new \ReflectionClass($this);

        $this->assertTrue($reflection->isFinal());
    }

    final public function testFixerTestCoversCorrectFixer()
    {
        $reflection      = new \ReflectionClass($this);
        $fixerReflection = new \ReflectionClass($this->fixer);

        $this->assertSame($reflection->getShortName(), $fixerReflection->getShortName() . 'Test');
    }

    /**
     * @dataProvider provideFixerCases
     *
     * @param array      $expected
     * @param array|null $input
     */
    final public function testFixer(array $expected, array $input = null)
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

    abstract public function provideFixerCases();
}
