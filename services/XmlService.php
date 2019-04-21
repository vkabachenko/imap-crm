<?php

namespace app\services;

use app\services\path\PathInterface;
use bupy7\xml\constructor\XmlConstructor;
use yii\base\Component;

class XmlService extends Component
{
    /**
     * @var XmlConstructor
     */
    private $xmlConstructor;
    /**
     * @var PathInterface
     */
    private $path;

    public function __construct(XmlConstructor $xmlConstructor, PathInterface $path, $config = [])
    {
        $this->path = $path;
        $this->xmlConstructor = $xmlConstructor;
        parent::__construct($config);
    }

    /**
     * @param $in array
     */
    public function create($in)
    {
        $xml = $this->xmlConstructor->fromArray($in)->toOutput();
        file_put_contents($this->path->getPath(), $xml);
    }
}