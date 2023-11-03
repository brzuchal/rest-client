<?php

declare(strict_types=1);

namespace Brzuchal\RestClient;

use function preg_match;
use function preg_match_all;
use function str_replace;

final class UriExpander
{
    /** @param non-empty-string $uri */
    public function __construct(private readonly string $uri)
    {
    }

    /**
     * @param array<string,mixed> $uriVariables
     *
     * @return non-empty-string
     */
    public function expand(array $uriVariables): string
    {
        preg_match_all('~\{(?<variable>[^}]*)}~', $this->uri, $matches);
        $vars = [];
        foreach ($matches['variable'] as $name) {
            $vars[] = $uriVariables[$name] ?? '';
        }

        return str_replace($matches[0], $vars, $this->uri);
    }

    public static function supports(string $uri): bool
    {
        return preg_match('~(?!\{[./;?&=,!@|+#])\{(?<variable>[^},]*)}~', $uri) > 0;
    }
}
