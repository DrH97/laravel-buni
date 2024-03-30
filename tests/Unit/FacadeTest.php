<?php


use DrH\Buni\Exceptions\BuniException;
use DrH\Buni\Facades\BuniStk;
use Illuminate\Support\Facades\Config;

test('stk push facade', function () {
    Config::set('buni.url', 'http://localhost');

    BuniStk::push(1, '0722000000', 'test');

})->throws(BuniException::class);
