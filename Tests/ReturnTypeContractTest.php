<?php

declare(strict_types=1);

namespace FastPix\Sdk\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Every resource method must hand its deserialized 2xx body to a property of
 * the declared Operations\*Response wrapper whose type is exactly that class.
 * Scans src/<Resource>.php as text so drift fails by method name.
 */
final class ReturnTypeContractTest extends TestCase
{
    private const MIN_METHODS = 69;

    /** @return array<string, array{string, string, string, ?string, ?string}> */
    public static function methods(): array
    {
        $cases = [];
        foreach (glob(__DIR__.'/../src/*.php') ?: [] as $file) {
            $src = file_get_contents($file) ?: '';
            preg_match_all('/public function (\w+)\([^)]*\): Operations\\\\(\w+)\s*\{/', $src, $heads, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
            foreach ($heads as $i => $h) {
                $start = $h[0][1];
                $end = isset($heads[$i + 1]) ? $heads[$i + 1][0][1] : strlen($src);
                $body = substr($src, $start, $end - $start);
                $cls = null;
                $key = null;
                if (preg_match('/matchStatusCodes\(\$statusCode, \[\'2\d\d\'\]\).*?deserialize\(\$responseData, \'([^\']+)\'.*?return new Operations\\\\\w+\(.*?(\w+): \$obj\)/s', $body, $m)) {
                    // Inline shape: deserialize then construct the wrapper in the 2xx branch.
                    $cls = $m[1];
                    $key = $m[2];
                } elseif (preg_match('/matchStatusCodes\(\$statusCode, \[\'2\d\d\'\]\).*?return \$this->build\w+Response\([^;]*?(\'[^\']+\'|self::\w+), false\);.*?(\w+): \$asError \? null : \$obj,/s', $body, $m)) {
                    // Helper shape: the 2xx branch passes the body class (literal or class constant) to a private build*Response helper.
                    $cls = str_starts_with($m[1], 'self::') ? self::constant($src, substr($m[1], 6)) : trim($m[1], "'");
                    $key = $m[2];
                }
                $cases[basename($file, '.php').'::'.$h[1][0]] = [basename($file, '.php'), $h[1][0], $h[2][0], $cls, $key];
            }
        }

        return $cases;
    }

    private static function constant(string $src, string $name): ?string
    {
        return preg_match('/private const '.$name." = '([^']+)';/", $src, $m) ? $m[1] : null;
    }

    public function test_scan_covers_the_whole_surface(): void
    {
        self::assertGreaterThanOrEqual(self::MIN_METHODS, count(self::methods()));
    }

    #[DataProvider('methods')]
    public function test_declared_response_carries_deserialized_class(string $resource, string $method, string $response, ?string $cls, ?string $key): void
    {
        $wrapper = '\\FastPix\\Sdk\\Models\\Operations\\'.$response;
        self::assertTrue(class_exists($wrapper), "$resource::$method declares missing $wrapper");
        self::assertNotNull($cls, "$resource::$method: could not locate the 2xx deserialization");
        $prop = new \ReflectionProperty($wrapper, (string) $key);
        $type = $prop->getType();
        self::assertInstanceOf(\ReflectionNamedType::class, $type);
        self::assertSame(ltrim($cls, '\\'), $type->getName(), "$resource::$method deserializes $cls but $wrapper::\$$key is typed {$type->getName()}");
    }
}
