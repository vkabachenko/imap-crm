<?php


namespace app\models;


interface EMailInterface
{
    public function setAttachmentPath();
    public function clearAttributes();
}