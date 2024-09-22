<?php

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

describe('Architectural Tests', function () {

    test('globals')
        ->expect(['dd', 'dump', 'ray', 'env', 'var_dump', 'print_r', 'var_export', 'die'])
        ->not->toBeUsed();

    test('traits')
        ->expect('App\Models\Traits')
        ->toBeTraits()
        ->expect('App\Traits')
        ->toBeTraits();

    test('strict types')
        ->expect('App')
        ->toUseStrictTypes();

    test('controllers')
        ->expect('App\Http\Controllers')
        ->toHaveSuffix('Controller')
        ->classes->not->toBeFinal()
        ->classes->not->toExtend(Controller::class)
        ->toImplementNothing();

    pest()->extend(TestCase::class)
        ->use(LazilyRefreshDatabase::class)
        ->beforeEach(function () {
            Http::preventStrayRequests();
        });
});
