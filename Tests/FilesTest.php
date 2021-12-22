<?php

use PHPUnit\Framework\TestCase;

use function Chase\Validator\validate;

class FilesTest extends TestCase
{

    protected function setUp(): void
    {
        $this->base = __DIR__ . '/Files/';
    }

    protected function tearDown(): void
    {
        $this->base = null;
    }

    public function testFileRequired()
    {
        $rule = ['fileRequired'];
        $v1 = validate("fake/path.fake")->withRules($rule);
        $v2 = validate($this->base)->withRules($rule);
        $v3 = validate($this->base . "pdf.pdf")->withRules($rule);

        $this->assertEquals($v1->passes(), false);
        $this->assertEquals($v2->passes(), false);
        $this->assertEquals($v3->passes(), true);
    }

    public function testFileMinSize()
    {

        $path = $this->base . "doc.doc"; // Size of about 9.5kb - 9.7kb

        $v1 = validate($path)->withRules(['fileMinSize:' . (5 * 1024)]);
        $v2 = validate($path)->withRules(['fileMinSize:' . (15 * 1024)]);

        $this->assertEquals($v1->passes(), true);
        $this->assertEquals($v2->passes(), false);
    }

    public function testFileMaxSize()
    {
        $path = $this->base . "doc.doc"; // Size of about 9.5kb - 9.7kb

        $v1 = validate($path)->withRules(['fileMaxSize:' . (5 * 1024)]);
        $v2 = validate($path)->withRules(['fileMaxSize:' . (15 * 1024)]);

        $this->assertEquals($v1->passes(), false);
        $this->assertEquals($v2->passes(), true);
    }

    public function testFileImage()
    {

        $v1 = validate($this->base . 'jpg.jpg')->withRules(['fileImage']);
        $v2 = validate($this->base . 'doc.doc')->withRules(['fileImage']);
        $v3 = validate($this->base . 'pdf.pdf')->withRules(['fileImage']);
        $v4 = validate($this->base . 'png.png')->withRules(['fileImage']);

        $this->assertEquals($v1->passes(), true);
        $this->assertEquals($v2->passes(), false);
        $this->assertEquals($v3->passes(), false);
        $this->assertEquals($v4->passes(), true);
    }

    public function testFilePdf()
    {

        $v1 = validate($this->base . 'jpg.jpg')->withRules(['filePdf']);
        $v2 = validate($this->base . 'doc.doc')->withRules(['filePdf']);
        $v3 = validate($this->base . 'pdf.pdf')->withRules(['filePdf']);
        $v4 = validate($this->base . 'png.png')->withRules(['filePdf']);

        $this->assertEquals($v1->passes(), false);
        $this->assertEquals($v2->passes(), false);
        $this->assertEquals($v3->passes(), true);
        $this->assertEquals($v4->passes(), false);
    }

    public function testFileOffice()
    {

        $v1 = validate($this->base . 'jpg.jpg')->withRules(['fileOffice']);
        $v2 = validate($this->base . 'doc.doc')->withRules(['fileOffice']);
        $v3 = validate($this->base . 'pdf.pdf')->withRules(['fileOffice']);
        $v4 = validate($this->base . 'xls.xls')->withRules(['fileOffice']);

        $this->assertEquals($v1->passes(), false);
        $this->assertEquals($v2->passes(), true);
        $this->assertEquals($v3->passes(), false);
        $this->assertEquals($v4->passes(), true);
    }

    public function testMimes()
    {

        $v1 = validate($this->base . 'jpg.jpg')->withRules(['mimes:image/jpeg']);
        $v2 = validate($this->base . 'png.png')->withRules(['mimes:image/png']);

        $this->assertEquals($v1->passes(), true);
        $this->assertEquals($v2->passes(), true);
    }

    public function testMimeTypes()
    {

        $v1 = validate($this->base . 'jpg.jpg')->withRules(['mimeTypes:jpeg']);
        $v2 = validate($this->base . 'png.png')->withRules(['mimeTypes:png']);

        $this->assertEquals($v1->passes(), true);
        $this->assertEquals($v2->passes(), true);
    }
}
