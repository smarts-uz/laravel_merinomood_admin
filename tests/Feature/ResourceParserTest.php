<?php

namespace Arniro\Admin\Tests\Feature;

use Arniro\Admin\Parsers\ResourceParser;
use Arniro\Admin\Tests\Fixtures\User;
use Arniro\Admin\Tests\Fixtures\UserResource;
use Arniro\Admin\Tests\IntegrationTest;

class ResourceParserTest extends IntegrationTest
{
    protected $parser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = resolve(ResourceParser::class);
    }

    /** @test */
    public function it_can_return_resource_class()
    {
        $this->assertEquals(UserResource::class, $this->parser->class('user-resources'));
    }

    /** @test */
    public function it_can_return_resource_instance()
    {
        $this->assertInstanceOf(UserResource::class, $this->parser->instance('user-resources'));
    }

    /** @test */
    public function it_can_return_resource_instance_with_model_included()
    {
        $resource = $this->parser->instance('user-resources', 1);

        $this->assertInstanceOf(User::class, $resource->resource());
    }

    /** @test */
    public function it_can_return_resource_underlying_model_instance()
    {
        $this->assertEquals($this->user->id, $this->parser->model('user-resources', $this->user->id)->id);
    }
}
