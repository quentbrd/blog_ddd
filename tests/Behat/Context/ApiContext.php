<?php

declare(strict_types=1);

namespace Blog\Tests\Behat\Context;

use Imbo\BehatApiExtension\Context\ApiContext as BaseApiContext;
use Psr\Http\Message\ResponseInterface;

class ApiContext extends BaseApiContext
{
    /**
     * Dump last received response.
     *
     * @Then dump last response
     */
    public function dumpLastReponse(): void
    {
        $this->requireResponse();

        /** @var ResponseInterface $response */
        $response = $this->response;

        dump($response->getStatusCode());
        dump((string) $response->getBody());
    }
}
