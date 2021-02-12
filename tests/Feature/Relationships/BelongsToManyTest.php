<?php

namespace Arniro\Admin\Tests\Feature\Relationships;

use Arniro\Admin\Http\Requests\AdminRequest;
use Arniro\Admin\Http\Resources\ResourceView;
use Arniro\Admin\Tests\Fixtures\Product;
use Arniro\Admin\Tests\Fixtures\ProductResource;
use Arniro\Admin\Tests\Fixtures\Tag;
use Arniro\Admin\Tests\Fixtures\TagResource;
use Arniro\Admin\Tests\IntegrationTest;
use Illuminate\Support\Collection;

class BelongsToManyTest extends IntegrationTest
{
    /**
     * @var AdminRequest
     */
    protected $request;

    /**
     * @var Product
     */
    protected $product;

    /**
     * @var Collection
     */
    protected $tags;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = resolve(AdminRequest::class);
        $this->product = factory(Product::class)->create();
        $this->tags = factory(Tag::class, 5)->create([
            'note' => 'Tag note'
        ]);
    }

    /** @test */
    public function product_can_list_associated_tags()
    {
        $this->product->tags()->attach($this->tags->take(3)->pluck('id'));

        $productResource = new ProductResource($this->product);
        $field = $productResource->resolveField('tags', TagResource::class)
            ->toResponse($productResource);

        $this->assertCount(3, $field['value']);
    }

    /** @test */
    public function it_can_list_additional_fields_in_index_page()
    {
        $this->product->tags()->attach($tagId = $this->tags->first()->id, [
            'note' => 'Some note'
        ]);

        $this->json('GET', 'admin/api/resources/product-resources/' . $this->product->id)
            ->assertJson([
                'relationships' => [
                    0 => [
                        'resources' => [
                            'resources' => [
                                0 => [
                                    'fields' => [
                                        [
                                            'attribute' => 'tags',
                                            'value' => $tagId
                                        ],
                                        [
                                            'attribute' => 'note',
                                            'value' => 'Some note'
                                        ]
                                    ],
                                    'view' => ResourceView::INDEX
                                ]
                            ],
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_can_has_custom_label()
    {
        $this->product->tags()->attach($this->tags->first()->id, [
            'note' => 'Some note'
        ]);

        $this->json('GET', 'admin/api/resources/product-resources/'. $this->product->id)
            ->assertJson([
                'relationships' => [
                    0 => [
                        'label' => 'Custom tags label'
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_can_show_page_to_attach_a_tag_to_a_product()
    {
        $this->json('GET', $this->attachUrl(), $this->viaParams())
            ->assertJson([
                'label' => 'Product Resources',
                'name' => 'product-resources',
            ])->assertJsonCount(5, 'fields.0.options');
    }

    /** @test */
    public function it_can_show_additional_fields_in_attaching_page()
    {
        $this->json('GET', $this->attachUrl(), $this->viaParams())
            ->assertJson([
                'fields' => [
                    [],
                    [
                        'attribute' => 'note'
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_can_attach_a_tag_to_a_product_with_additional_fields()
    {
        $this->json('POST', $this->attachUrl(), $this->viaParams())
            ->assertJsonValidationErrors('tags');

        $this->json('POST', $this->attachUrl(), array_merge($this->viaParams(), [
            'tags' => $tagId = $this->tags->first()->id,
            'note' => 'Some note'
        ]));

        $this->assertEquals($tagId, $this->product->tags()->first()->id);
        $this->assertTrue($this->product->tags()->wherePivot('note', 'Some note')->exists());
    }

    /** @test */
    public function it_cannot_attach_the_same_tag_to_a_product_twice()
    {
        $this->json('POST', $this->attachUrl(), array_merge($this->viaParams(), [
            'tags' => $tagId = $this->tags->first()->id,
            'note' => 'Some note'
        ]));

        $this->json('POST', $this->attachUrl(), array_merge($this->viaParams(), [
            'tags' => $tagId,
            'note' => 'Some note'
        ]))->assertJsonValidationErrors('tags');

        $this->assertCount(1, $this->product->tags);
    }

    /** @test */
    public function it_calls_post_hook_after_attaching()
    {
        $this->json('POST', $this->attachUrl(), array_merge($this->viaParams(), [
            'tags' => $this->tags->first()->id,
            'note' => 'Some note'
        ]));

        $this->assertEquals('changed', $this->product->fresh()->name);
    }

    /** @test */
    public function it_can_show_page_to_edit_attached()
    {
        $this->withoutExceptionHandling();

        $this->product->tags()->attach($tagId = $this->tags->first()->id);

        $this->json('GET', $this->editAttachUrl(), $this->viaParams())
            ->assertJson([
                'label' => 'Product Resources',
                'name' => 'product-resources',
            ])->assertJsonCount(5, 'fields.0.options')
            ->assertJson([
                'fields' => [
                    [
                        'value' => $tagId,
                        'disabled' => true
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_can_show_additional_fields_in_edit_attached_page()
    {
        $this->product->tags()->attach($tagId = $this->tags->first()->id, ['note' => 'Some note']);

        $this->json('GET', $this->editAttachUrl(), $this->viaParams())
            ->assertJson([
                'fields' => [
                    [
                        'value' => $tagId,
                        'disabled' => true
                    ],
                    [
                        'attribute' => 'note',
                        'value' => 'Some note'
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_can_update_attached_resource_with_additional_fields()
    {
        $this->product->tags()->attach($this->tags);

        $this->json('PUT', $this->editAttachUrl(), array_merge($this->viaParams(), [
            'tags' => $this->tags[1]->id,
            'note' => 'Changed'
        ]))->assertJsonValidationErrors(['tags']);

        $this->json('PUT', $this->editAttachUrl(), array_merge($this->viaParams(), [
            'tags' => $tagId = $this->tags->first()->id,
            'note' => 'Changed'
        ]));

        $this->assertEquals('Changed', $this->product->tags()->find($tagId)->pivot->note);
    }

    /** @test */
    public function it_can_detach_a_tag_from_a_product()
    {
        $this->withoutExceptionHandling();
        $this->product->tags()->attach($this->tags);

        $this->json('DELETE', $this->detachUrl(), $this->viaParams())
            ->assertJsonCount(4, 'resources');

        $this->assertNull($this->product->tags()->find(1));
    }

    protected function attachUrl()
    {
        return "admin/api/resources/product-resources/{$this->product->id}/attach/tag-resources";
    }

    protected function editAttachUrl()
    {
        return "admin/api/resources/product-resources/{$this->product->id}/attach/tag-resources/edit/{$this->tags->first()->id}";
    }

    protected function detachUrl()
    {
        return "admin/api/resources/tag-resources/{$this->tags->first()->id}/detach/product-resources/{$this->product->id}";
    }

    protected function viaParams()
    {
        return [
            'viaRelationship' => 'tags',
        ];
    }
}
