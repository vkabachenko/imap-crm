<?php

namespace app\services;

use bupy7\xml\constructor\XmlConstructor;

class XmlService
{
    /**
     * @var XmlConstructor
     */
    private $xmlConstructor;

    public function __construct()
    {
        $this->xmlConstructor = new XmlConstructor();
    }

    /**
     * @param $in array
     */
    public function create($in)
    {
        $xml = $this->xmlConstructor->fromArray($in)->toOutput();
        $path = \Yii::getAlias('@webroot/xml/') . date("YmdHis") . '.xml';
        file_put_contents($path, $xml);
    }
}