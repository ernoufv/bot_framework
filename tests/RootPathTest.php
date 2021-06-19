<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class RootPathTest extends TestCase
{
    /**
     * A basic test.
     *
     * @return void
     */
    public function testRootPath()
    {
        $this->get('/');

        $this->assertEquals(
            'Bot Root', $this->response->getContent()
        );
    }
}
