<?php

namespace Tests;

class ProcOpenMock
{
    const COMMAND_PREFIX = "exec 'composer' '";

    public static function call(&...$args)
    {
        if (\mb_strpos($args[0], self::COMMAND_PREFIX) === 0) {
            return \proc_open(
                \sprintf('exit %d', self::mock(\mb_substr($args[0], \mb_strlen(self::COMMAND_PREFIX))) ? 0 : 1),
                [],
                $pipes
            );
        }

        return \proc_open(...$args);
    }

    /**
     * @param string $command
     *
     * @return bool
     */
    private static function mock($command)
    {
        if (\mb_strpos($command, 'validate') === 0) {
            return \json_decode(\file_get_contents(__DIR__ . '/composer.json')) !== null;
        }
        if (\mb_strpos($command, 'require') === 0) {
            if (\mb_strpos($command, 'dummy/dummy') > 0) {
                return false;
            }
            $composer = \json_decode(\file_get_contents(__DIR__ . '/composer.json'), true);
            foreach ($composer['require'] as &$require) {
                $require = '^1.0';
            }
            \file_put_contents(
                __DIR__ . '/composer.json',
                \json_encode($composer, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\n"
            );
        }

        return true;
    }
}
