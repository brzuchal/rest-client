<?php declare(strict_types=1);

namespace Brzuchal\RestClient\Attributes;

/**
 * @author Michał Brzuchalski <michal.brzuchalski@gmail.com>
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class PostExchange extends EntityExchange
{
}
