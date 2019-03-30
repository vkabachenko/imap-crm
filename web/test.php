<?php
$req = new HttpRequest('http://example.com/feed.rss', HttpRequest::METH_GET);
if (version_compare(PHP_VERSION, '5.6.0', '<')) {
    //correct ssl validation on php 5.5 is a pain, so disable
    $req->setConfig('ssl_verify_host', false);
    $req->setConfig('ssl_verify_peer', false);
}
