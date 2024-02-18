<?php

use function Pest\Laravel\artisan;

describe('CommandTest', function () {
    it('can debug the serializer with a command', function () {
        artisan('debug:serializer SomeDto')->assertExitCode(0);
    });
});

class SomeDto {
    private string $name;
}
