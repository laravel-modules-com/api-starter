<?php

use function Pest\Laravel\get;

it('returns a successful response', function () {
    get('/')->assertRedirect('docs');
});
