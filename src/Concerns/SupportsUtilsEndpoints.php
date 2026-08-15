<?php

namespace RedirectPizza\PhpSdk\Concerns;

use RedirectPizza\PhpSdk\Dto\QrCode;
use RedirectPizza\PhpSdk\Dto\RedirectTestResult;
use RedirectPizza\PhpSdk\RedirectPizza;
use RedirectPizza\PhpSdk\Requests\Utils\GenerateQrCodeRequest;
use RedirectPizza\PhpSdk\Requests\Utils\TestRedirectRequest;
use Saloon\Http\Response;

/** @mixin RedirectPizza */
trait SupportsUtilsEndpoints
{
    public function testRedirect(string $url): RedirectTestResult
    {
        return $this->send(new TestRedirectRequest($url))->dto();
    }

    public function qrCode(string $url, string $format = 'json'): QrCode|Response
    {
        $response = $this->send(new GenerateQrCodeRequest($url, $format));

        if ($format !== 'json') {
            return $response;
        }

        return $response->dto();
    }
}
