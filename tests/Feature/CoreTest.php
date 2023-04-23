<?php


use DrH\Buni\Library\Endpoints;
use GuzzleHttp\Psr7\Response;

it('sends request', function () {
    $this->mock->append(
        new Response(200, ['Content_type' => 'application/json'],
            json_encode($this->mockResponses['auth']['success'])));
    $this->mock->append(
        new Response(200, ['Content_type' => 'application/json'],
            json_encode($this->mockResponses['auth']['success'])));

    expect($this->core->request(Endpoints::AUTH, []))->toBeArray();
});
