# phalcon-translate-adapter-nestednativearray

翻訳の変換リストを多次元配列で定義出来るように対応したアダプタ

## Install

```json
{
    "require": {
        "muroi/phalcon-translate-adapter-nestednativearray": "*"
    }
}
```

or

``` shell
php composer.phar require muroi/phalcon-translate-adapter-nestednativearray
```

## Usage

### 翻訳リストファイルを作成

```
app/messages/ja.php
app/messages/en.php
```

* ファイルの中身

``` php
<?php
// app/messages/ja.php
$messages = [
    'test' => [
        'hello' => 'こんにちは'
    ],
    'test1' => [
        'test2' => [
            'test3' => 'テスト3'
        ]
    ]
];
```

### コントローラで呼び出す

``` php
<?php

use Phalcon\Mvc\Controller;
use Muroi\Phalcon\Translate\Adapter\NestedNativeArray;

class UserController extends Controller
{
    protected function getTranslation()
    {
        // Ask browser what is the best language
        $language = $this->request->getBestLanguage();

        $translationFile = "app/messages/" . $language . ".php";

        // Check if we have a translation file for that lang
        if (file_exists($translationFile)) {
            require $translationFile;
        } else {
            // Fallback to some default
            require "app/messages/en.php";
        }

        // Return a translation object
        return new NestedNativeArray(
            [
                "content" => $messages,
            ]
        );
    }

    public function indexAction()
    {
        $this->view->t = $this->getTranslation();
    }
}
```

### View

``` volt
<!-- こんにちは -->
<p>{{ t._("test.hello") }}</p>
```

## See

[Phalcon Translate](https://docs.phalconphp.com/en/latest/reference/translate.html)
