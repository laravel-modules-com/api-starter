<?php

pest()->extend(Tests\TestCase::class)
    ->in(__DIR__, '../Modules/*/tests/*');
