<?php


namespace app\services\path;


class XmlMailPath implements PathInterface
{
    public function getPath()
    {
        return \Yii::getAlias('@webroot/xml/') . date("YmdHis") . '.xml';
    }

}