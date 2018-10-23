<?php
/**
 * Created by PhpStorm.
 * User: XYX
 * Date: 2017/4/29
 * Time: 15:21
 */

namespace App\Markdown;


class Markdown
{

    protected $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }
    public function markdown($text){
        $html = $this->parser->makeHtml($text);
        return $html;
    }
}