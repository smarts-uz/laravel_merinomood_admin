<?php

namespace Arniro\Admin\Tests\Feature;

use Arniro\Admin\Fields\Field;
use Arniro\Admin\Fields\Text;
use Arniro\Admin\Parsers\ValidationRulesParser;
use Arniro\Admin\Tests\Fixtures\Product;
use Arniro\Admin\Tests\IntegrationTest;

class ValidationRulesParserTest extends IntegrationTest
{
    /**
     * @var Field
     */
    protected $field;

    /**
     * @var ValidationRulesParser
     */
    protected $parser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->field = Text::make('Name', 'name');
        $this->parser = new ValidationRulesParser($this->field, [
            'required',
            'max:255' => 'Specify allowed length.'
        ]);
    }

    /** @test */
    public function it_normalizes_rules()
    {
        $parser = new ValidationRulesParser($this->field, 'required');

        $this->assertEquals(['required'], $parser->normalize('required'));
        $this->assertEquals(['required', 'max:255'], $parser->normalize(['required', 'max:255']));
        $this->assertEquals(['required', 'max:255'], $parser->normalize([['required', 'max:255']]));
    }

    /** @test */
    public function it_can_return_only_rules_which_dont_have_messages()
    {
        $this->assertEquals(['required'], $this->parser->getRaw());
    }

    /** @test */
    public function it_can_return_only_rules_which_have_messages()
    {
        $this->assertEquals(['max:255' => 'Specify allowed length.'], $this->parser->getMessageables());
    }

    /** @test */
    public function it_can_gather_only_rules()
    {
        $this->assertEquals(['required', 'max:255'], $this->parser->gather());
    }

    /** @test */
    public function it_substitute_rule_resource_id_with_model_id()
    {
        $rules = ValidationRulesParser::format(collect([
            'unique:products,name,{{resourceId}}'
        ]), $product = factory(Product::class)->create())->toArray();

        $this->assertEquals(['unique:products,name,' . $product->id], $rules);
    }
}
