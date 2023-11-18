<?php

namespace Tests\UnitTest\CollectEngine\Operation;

use CollectEngine\Contract\ExtractorInput;
use CollectEngine\DataModel\Resources;
use CollectEngine\Operation\Html2JsonExtractOperation;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class Html2JsonExtractOperationTest extends TestCase
{
    protected function setUp(): void
    {

    }

    public function testExtract()
    {
        $json = <<<JSON
{
  "fields": [
    {
      "name": "title",
      "type": "text",
      "selector": "//head/title/text()",
      "converter": [
        "trim"
      ]
    },
    {
      "name": "link",
      "type": "list",
      "selector": "//div[@class='content']//a/@href",
      "converter": [
        "trim"
      ]
    },
    {
      "name": "person",
      "type": "object",
      "selector": [
        {
          "name": "name",
          "type": "text",
          "selector": "//div[@class='person']//div[@class='name']/text()"
        },
        {
          "name": "age",
          "type": "text",
          "selector": "//div[@class='person']//div[@class='age']/text()"
        }
      ],
      "converter": [
        "trim"
      ]
    },
    {
      "name": "persons",
      "type": "list",
      "rows_selector": "//div[@class='row']",
      "selector": [
        {
          "name": "name",
          "type": "text",
          "selector": "//div[@class='name']/text()"
        },
        {
          "name": "age",
          "type": "text",
          "selector": "//div[@class='age']/text()"
        }
      ],
      "converter": [
        "trim"
      ]
    }
  ]
}
JSON;
        $html = <<<'HTML'
<!DOCTYPE html>
<html>
<head>
<title>  test  </title>
</head>
<body>
<h1>Hello</h1>
<div class="content">
<a href="https://example.com/aaa">example</a>
<a href="https://example.com/bbb">example2</a>
</div>
<div class="person"><div class="name">taro</div><div class="age">33</div></div>

<div class="persons">
<div class="row"><div class="name">jiro</div><div class="age">22</div></div>
<div class="row"><div class="name">sabu</div><div class="age">11</div></div>
</div>
</body>
</html>
HTML;
        $tempnam = (new Filesystem())->tempnam(sys_get_temp_dir(), '');
        file_put_contents($tempnam, $html);

        $result = (new Html2JsonExtractOperation())->extract(
            new ExtractorInput(
                resources: (new Resources())->import(
                    key: 'export',
                    filePath: $tempnam,
                ),
                operation: json_decode($json, true),
            ),
        );
        $this->assertSame(1, 1);
        $this->assertSame([
            'title' => 'test',
            'link' => [
                'https://example.com/aaa',
                'https://example.com/bbb',
            ],
            'person' => [
                'name' => 'taro',
                'age' => '33',
            ],
            'persons' => [
                [
                    'name' => 'jiro',
                    'age' => '22',
                ],
                [
                    'name' => 'sabu',
                    'age' => '11',
                ],
            ],
        ], json_decode(file_get_contents($result->resources->export()), true));
    }
}