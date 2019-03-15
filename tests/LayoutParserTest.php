<?php

namespace Humweb\Tests\ThemeManager;

use Humweb\ThemeManager\Editor\Exceptions\TemplateFieldsNotFound;
use Humweb\ThemeManager\Editor\LayoutParser;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class AddTransactionTest
 *
 * @package Humweb\Teams\Tests
 */
class LayoutParserTest extends TestCase
{
    use DatabaseTransactions;

    protected $parser;


    public function setUp()
    {
        parent::setUp();

        $this->parser = new LayoutParser();
    }


    /**
     * @test
     */
    public function it_can_parse_template_attributes()
    {
        $sections = $this->parser->parseFile(__DIR__.'/Stubs/template.html');

        $this->assertEquals('header', $sections[0]);
        $this->assertEquals('sidebar', $sections[1]);
        $this->assertEquals('body', $sections[2]);
        $this->assertEquals('footer', $sections[3]);
    }


    /**
     * @test
     */
    public function it_will_throw_exception_when_no_attributes_found()
    {
        $this->parser->setAttributeName('foo');
        $this->expectException(TemplateFieldsNotFound::class);
        $this->parser->parseFile(__DIR__.'/Stubs/template.html');
    }

}
