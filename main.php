<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'client_photolab.php';


$comboId = '5635874';
$comboIdAdvanced = '3124589';

$client = new ClientPhotolab();


$resoursesFilename = 'resources.zip';
if (!file_exists($resoursesFilename)) {
    $client->downloadFile('http://soft.photolab.me/samples/resources.zip', $resoursesFilename);
}

$contentFilename = 'girl.jpg';
if (!file_exists($contentFilename)) {
    $client->downloadFile('http://soft.photolab.me/samples/girl.jpg', $contentFilename);
}


$contentUrl = $client->imageUpload($contentFilename);
echo sprintf("content url: %s" . PHP_EOL, $contentUrl);

$templateName = $client->templateUpload($resoursesFilename);
echo sprintf("template name: %s" . PHP_EOL, $templateName);


$resultUrl = $client->templateProcess($templateName, [[
    'url' => $contentUrl,
    'rotate' => 0,
    'flip' => 0,
    'crop' => '0,0,1,1'
]]);
echo sprintf("for template_name: %s, result_url: %s" . PHP_EOL, $templateName, $resultUrl);

foreach ([5635874, 3124589] as $comboId) {
    $originalContentUrl = $contentUrl;
    echo("===" . PHP_EOL);

    echo sprintf("start process combo_id: %s" . PHP_EOL, $comboId);

    $steps = $client->photolabStepsAdvanced($comboId);
    $steps = $steps->steps;
    foreach ($steps as $step) {
        $templateName = $step->id;
        $contents = [];
        foreach ($step->image_urls as $i => $url) {
            if (!$url) {
                $url = $originalContentUrl;
            }
            $contents[] = [
                'url'       => $url,
                'rotate'    => 0,
                'flip'      => 0,
                'crop'      => '0,0,1,1'
            ];
        }
        if (!$contents) {
            $contents[] = [
                'url'       => $originalContentUrl,
                'rotate'    => 0,
                'flip'      => 0,
                'crop'      => '0,0,1,1'
            ];
        }
        $resultUrl = $client->templateProcess($templateName, $contents);
        $originalContentUrl = $resultUrl;
        echo sprintf("--- for template_name: %s, result_url: %s" . PHP_EOL, $templateName, $resultUrl);
    }
    echo sprintf("result_url: %s" . PHP_EOL, $resultUrl);
}
