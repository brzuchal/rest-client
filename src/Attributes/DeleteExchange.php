<?php

declare(strict_types=1);

namespace Brzuchal\RestClient\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class DeleteExchange extends EntityExchange
{
}
