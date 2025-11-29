<?php

use Cotopaco\Factus\Http\FactusHttpClient;

describe("Authentication", function(){

    it('Get Access Token and Cache it correctly', function () {
        $client = new FactusHttpClient();

        $token = $client->getAccessToken();

        expect($token)->toBeString()->not->toBeEmpty();

        $token2 = $client->getAccessToken();

        /* Test cache */
        expect($token2)->toBe($token);

    });


})->group('api', 'authentication');
