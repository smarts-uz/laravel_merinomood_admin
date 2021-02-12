<?php

namespace Arniro\Admin\Tests\Unit;

use Arniro\Admin\Fields\Field;
use Arniro\Admin\Fields\Relationship;
use Arniro\Admin\Fields\Text;
use Arniro\Admin\Http\Requests\AdminRequest;
use Arniro\Admin\Tests\Fixtures\ProductResource;
use Arniro\Admin\Tests\IntegrationTest;

class ResourceTest extends IntegrationTest
{
    /** @test */
    public function it_can_resolve_any_field_by_its_attribute()
    {
        $resource = new ProductResource;

        $this->assertInstanceOf(Field::class, $resource->resolveField('name'));
    }

    /** @test */
    public function it_can_resolve_any_relationship_field_by_its_attribute_and_resource()
    {
        $resource = new ProductResource;
        $request = resolve(AdminRequest::class);

        $this->assertInstanceOf(Relationship::class, $resource->resolveField('tags', $request->resourceClass('tag-resources')));
    }


    /** @test */
    public function specific_page_fields_can_be_merged_with_the_default_ones()
    {
        $resource = new class extends ProductResource {
            public function indexFields()
            {
                return $this->merge([
                    Text::make('Description', 'des')
                ]);
            }
        };

        $this->assertCount(4, $resource->forView('index')->getFields());
    }
}
