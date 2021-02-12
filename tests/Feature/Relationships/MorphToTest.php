<?php

namespace Arniro\Admin\Tests\Feature\Relationships;

use Arniro\Admin\Fields\MorphTo;
use Arniro\Admin\Http\Requests\AdminRequest;
use Arniro\Admin\Tests\Fixtures\Comment;
use Arniro\Admin\Tests\Fixtures\CommentResource;
use Arniro\Admin\Tests\Fixtures\Post;
use Arniro\Admin\Tests\Fixtures\PostResource;
use Arniro\Admin\Tests\IntegrationTest;
use Illuminate\Support\Arr;

class MorphToTest extends IntegrationTest
{
    /** @var AdminRequest */
    protected $request;

    /**
     * @var Post
     */
    protected $post;

    /**
     * @var Comment
     */
    protected $comment;

    /**
     * @var CommentResource
     */
    protected $commentResource;

    /**
     * @var PostResource
     */
    protected $postResource;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = resolve(AdminRequest::class);

        $this->post = factory(Post::class, 3)->create()->first();

        $this->comment = factory(Comment::class)->create([
            'commentable_id' => $this->post->id
        ]);

        $this->postResource = new PostResource($this->post);
        $this->commentResource = new CommentResource($this->comment);
    }

    /** @test */
    public function it_can_return_types_including_model_and_label_of_connected_resources()
    {
        $this->assertEquals([
            Post::class => 'Post Resources'
        ], Arr::get($this->field()->toResponse($this->commentResource), 'types'));
    }

    /** @test */
    public function it_return_options_for_each_type()
    {
        factory(Post::class)->create([
            'name' => [
                'ru' => 'Post',
            ],
        ]);

        $options = Arr::get($this->field()->toResponse($this->commentResource), 'options');
        $postOptions = Arr::get($options, Post::class);

        $this->assertArrayHasKey(Post::class, $options);
        $this->assertCount(4, Arr::get($options, Post::class));
        $this->assertEquals('Post', end($postOptions));
    }

    /** @test */
    public function it_can_return_value_including_model_and_id()
    {
        $this->assertEquals([
            'attributeType' => Post::class,
            'attributeId' => $this->post->id
        ], Arr::get($this->field()->toResponse($this->commentResource), 'value'));
    }

    /** @test */
    public function it_can_return_value_from_via_resource_on_creation()
    {
        request()->merge([
            'viaResource' => 'post-resources',
            'viaResourceId' => $this->post->id,
            'viaRelationship' => 'commentable'
        ]);

        $this->assertEquals([
            'attributeType' => Post::class,
            'attributeId' => $this->post->id
        ], Arr::get($this->field()->toResponse($this->commentResource), 'value'));

        $this->assertTrue($this->field()->disabled);
    }

    /** @test */
    public function it_can_add_type_and_id_of_relation_to_the_model()
    {
        $data = [
            'commentable_type' => Post::class,
            'commentable_id' => $this->post->id
        ];

        $this->field()->store($this->request->merge($data), $model = new Comment);

        $this->assertEquals(Post::class, $model->commentable_type);
        $this->assertEquals($this->post->id, $model->commentable_id);

        $this->field()->update($this->request->merge($data), $this->comment);

        $this->assertEquals(Post::class, $this->comment->commentable_type);
        $this->assertEquals($this->post->id, $this->comment->commentable_id);
    }

    /** @test */
    public function it_can_save_data_from_request()
    {
        $this->json('POST', 'admin/api/resources/comment-resources', [
            'body' => 'Some body',
            'commentable_type' => Post::class,
            'commentable_id' => $this->post->id
        ]);

        $this->assertCount(2, $this->post->comments);

        $this->json('PUT', 'admin/api/resources/comment-resources/' . $this->comment->id, [
            'body' => 'Some body',
            'commentable_type' => Post::class,
            'commentable_id' => $this->post->id + 1
        ]);

        $this->assertCount(1, $this->post->fresh()->comments);

        $this->assertDatabaseHas('comments', [
            'commentable_type' => Post::class,
            'commentable_id' => $this->post->id + 1
        ]);
    }

    /** @test */
    public function it_can_be_validated()
    {
        $this->json('POST', 'admin/api/resources/comment-resources', [
            'body' => 'Some body',
            'commentable_type' => Post::class,
        ])->assertJsonValidationErrors(['commentable_id']);
    }

    /**
     * @return MorphTo
     */
    protected function field()
    {
        return $this->commentResource->resolveField('commentable');
    }

}
