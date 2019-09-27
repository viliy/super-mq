<?php

declare(strict_types=1);

/**
 * Class MqTest.
 */

namespace App\Tests;

use FastD\TestCase;

class MqTest extends TestCase
{
    public function testMonitor()
    {
        $request  = $this->request('GET', '/api/v1/monitor');
        $response = $this->handleRequest($request);

        $this->assertNotEmpty($response->toArray());
    }

    public function testSystem()
    {
        $request  = $this->request('GET', '/api/v1/system');
        $response = $this->handleRequest($request);

        $this->assertNotEmpty($response->toArray());
    }
}
