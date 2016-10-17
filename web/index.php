<?php

$updateUrl = "http://user:fd78rw42@intistelecom.asmsoft.ru/api/landing/get_landing/";
$updateSlug = "test_1";
$updateDir = "/tmp";
$updateCache = "/page-1-cache";

if (!file_exists($updateDir . $updateCache) || $_GET['update']=="force"){
    if(!file_exists($updateDir))
        mkdir($updateDir);

    $content = file_get_contents($updateUrl . $updateSlug);

    if($content){
        $content = json_decode($content);

        $html = getHtml($content);
        file_put_contents($updateDir . $updateCache, $html);
    }
}

echo file_get_contents($updateDir . $updateCache);

function getHtml($data){
    return '<!DOCTYPE html><html>'
        . getHeader($data)
        . $data->text
        . '</html>';
}

function getHeader($data){
    return '<head>'
    . getTitle($data)
    . staticMeta()
    . getDescription($data)
    . getKeywords($data)
    . getExtraContent($data)
    . getCss($data)
    . getScripts($data)
    .'</head>';
}

function getTitle($data){
    return '<title>'. $data->SEO->title ? $data->SEO->title : $data->title .'</title>';
}

function getDescription($data){
    if($data->SEO && $data->SEO->description)
        return '<meta name="description" content="'. $data->SEO->description.'">';
    return '';
}

function getKeywords($data){
    if($data->SEO && $data->SEO->keywords)
        return '<meta name="keywords" content="'. $data->SEO->keywords.'">';
    return '';
}

function getExtraContent($data){
    if($data->SEO && $data->SEO->keywords)
        return $data->SEO->keywords;
    return '';
}

function getCss($data){
    if($data->css)
        return '<style>' . $data->css . '</style>';
    else
        return '';
}

function getScripts($data){
    return $data->scripts ? $data->scripts : '';
}

function staticMeta(){
    return  '<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"/>'
            . '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">'
            . '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">'
            . '<meta name="robots" content="index, follow">';
}