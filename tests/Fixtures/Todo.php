<?php declare(strict_types=1);

namespace Brzuchal\RestClient\Tests\Fixtures;

final class Todo
{
    public function __construct(
        public readonly int $id,
        public readonly int $userId,
        public readonly string $title,
        public readonly bool $completed,
    )
    {
    }
}
