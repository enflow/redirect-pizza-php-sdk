<?php

use RedirectPizza\PhpSdk\RedirectPizza;
use Saloon\Http\Faking\MockClient;

uses()->in(__DIR__);

function redirectPizzaMock(): RedirectPizza
{
    MockClient::destroyGlobal();

    return new RedirectPizza('fake-api-token');
}

function markTestComplete(): void
{
    expect(true)->toBeTrue();
}
