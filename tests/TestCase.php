<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;


    protected function setUp(): void
    {
        parent::setUp();
        // Απενεργοποιούμε το Vite για τα tests, ώστε να μην προσπαθεί να φορτώσει assets που δεν υπάρχουν
        $this->withoutVite();
    }
}
