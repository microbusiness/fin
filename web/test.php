<?php

if ($_SERVER['REQUEST_METHOD']=='POST')
{
    $preparedData=array();
    foreach ($_POST as $key=>$item)
    {
        if ($key!='url')
        {
            $preparedData[$key]=$item;
        }
    }

    $json=json_encode($preparedData);

    $ch = curl_init($_POST['url']);
    $headers = array
    (
        'Accept:application/json, text/javascript, */*',
        'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3',
        'Accept-Encoding: deflate',
        'Accept-Charset: utf-8;q=0.7,*;q=0.7',
        'Content-Type: application/json; charset=UTF-8',
        'X-Requested-With: XMLHttpRequest'
    );

    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);


    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt ($ch, CURLOPT_POST, 1);
    curl_setopt ($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.1) Gecko/2008070208');
    $postdata = '';
    $postdata = curl_exec($ch);
    $newData=json_decode($postdata,true);
    header('Content-Type: application/json');
    echo json_encode($newData);
}
else
{
    if (array_key_exists('url',$_REQUEST))
    {
        $ch = curl_init($_REQUEST['url']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.1) Gecko/2008070208');
        $getdata = '';
        $getdata = curl_exec($ch);
        echo $getdata;
    }
    else
    {
        echo 'It works!';
    }
}