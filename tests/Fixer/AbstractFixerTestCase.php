<?php

declare(strict_types = 1);

namespace Tests\Fixer;

use ComposerJsonFixer\Fixer\Fixer;
use PHPUnit\Framework\TestCase;

abstract class AbstractFixerTestCase extends TestCase
{
    /** @var Fixer */
    private $fixer;

    final protected function setUp() : void
    {
        $reflection = new \ReflectionClass($this);
        $comment    = $reflection->getDocComment();

        if (\preg_match('/@covers\s+(\S+)/', $comment, $matches) !== 1) {
            throw new \UnexpectedValueException(\sprintf('Test %s must cover exactly one class.', \get_class($this)));
        }

        $fixerClass = $matches[1];

        $this->fixer = new $fixerClass();
    }

    final public function testFixerIsFinal() : void
    {
        $fixerReflection = new \ReflectionClass($this->fixer);

        static::assertTrue($fixerReflection->isFinal());
    }

    final public function testFixerImplementsFixer() : void
    {
        static::assertInstanceOf(Fixer::class, $this->fixer);
    }

    final public function testFixerTestIsFinal() : void
    {
        $reflection = new \ReflectionClass($this);

        static::assertTrue($reflection->isFinal());
    }

    final public function testFixerTestCoversCorrectFixer() : void
    {
        $reflection      = new \ReflectionClass($this);
        $fixerReflection = new \ReflectionClass($this->fixer);

        static::assertSame($reflection->getShortName(), $fixerReflection->getShortName() . 'Test');
    }

    final public function testFixerDescriptionIsNotEmpty() : void
    {
        static::assertNotEmpty($this->fixer->description());
    }

    /**
     * @dataProvider provideFixerCases
     */
    final public function testFixer(array $expected, array $input = null) : void
    {
        if ($expected === $input) {
            throw new \InvalidArgumentException('Input parameter must not be equal to expected parameter.');
        }

        if ($input !== null) {
            $fixed = $this->fixer->fix($input);
            static::assertSame($expected, $fixed);
        }

        $fixed = $this->fixer->fix($expected);
        static::assertSame($expected, $fixed);
    }

    abstract public function provideFixerCases() : array;

    final public function testPriority() : void
    {
        switch (\get_class($this->fixer)) {
            case 'ComposerJsonFixer\Fixer\ComposerKeysLowercaseFixer':
                $expected = 1;
                break;
            case 'ComposerJsonFixer\Fixer\ComposerKeysSortingFixer':
                $expected = -1;
                break;
            default:
                $expected = 0;
                break;
        }

        static::assertSame($expected, $this->fixer->priority());
    }
}
