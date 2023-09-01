<?php declare(strict_types=1);

namespace Brzuchal\RestClient\Factory;

use Brzuchal\RestClient\RestClientInterface;

/**
 * @author Michał Brzuchalski <michal.brzuchalski@gmail.com>
 */
trait RestClientTrait
{
    public function __construct(
        private RestClientInterface $restClient,
    ) {
    }
}
