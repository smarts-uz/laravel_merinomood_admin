<?php

use Arniro\Admin\Fields\File;
use Arniro\Admin\Http\Requests\AdminRequest;
use Arniro\Admin\Tests\Fixtures\Product;
use Arniro\Admin\Tests\IntegrationTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\FileBag;

class FileTest extends IntegrationTest {

    use RefreshDatabase;

    /**
     * @var AdminRequest
     */
    protected $request;

    /**
     * File field instance.
     *
     * @var File
     */
    protected $field;

    /**
     * @var Product
     */
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = resolve(Request::class);
        $this->field = File::make('File', 'file');
        $this->product = factory(Product::class)->make();
    }

    /** @test */
    public function it_stores_file_to_the_storage()
    {
        $this->request->files = new FileBag([
            'file' => UploadedFile::fake()->image('file.png')
        ]);

        $this->field->store($this->request, $this->product);

        $this->assertEquals('/file.png', $this->product->file);
        Storage::assertExists('file.png');

        Storage::delete('file.png');
    }

    /** @test */
    public function it_updates_file_in_the_storage()
    {
        $this->saveFile('file.png');
        $this->product->file = '/file.png';

        $this->request->files = new FileBag([
            'file' => UploadedFile::fake()->image('new-file.png')
        ]);

        $this->field->update($this->request, $this->product);

        $this->assertEquals('/new-file.png', $this->product->file);
        Storage::assertMissing('file.png');
        Storage::assertExists('new-file.png');

        Storage::delete('new-file.png');
    }

    /** @test */
    public function it_saves_as_it_is_when_string_is_passed()
    {
        $this->request->merge([
            'file' => 'image.png'
        ]);

        $this->field->update($this->request, $this->product);

        $this->assertEquals($this->product->file, 'image.png');
    }

    /** @test */
    public function it_removes_file_during_update_if_the_value_is_removed()
    {
        $this->saveFile('file.png');
        $this->product->file = '/file.png';

        $this->request->merge([
            'file' => ''
        ]);

        $this->field->update($this->request, $this->product);

        $this->assertEquals(null, $this->product->file);
        Storage::assertMissing('file.png');
    }

    /**
     * Save file with the given name to the storage.
     *
     * @param $name
     * @return string
     */
    protected function saveFile($name)
    {
        return (new \Arniro\Admin\File)->store('', UploadedFile::fake()->image($name));
    }

}