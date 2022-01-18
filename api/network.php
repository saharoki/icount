<?php

include "../vendor/autoload.php";
use Goutte\Client;

$country = $_GET['country'] ?? '';
$link = $_GET['link'] ?? '';

if(empty($country) || empty($link)){
    echo '{}';
} else {

    $client = new Client();
    $crawler = $client->request('GET', 'http://en.wikipedia.org'.$link);

    $data = [];
    $crawler->filter('h4 span a')->each(function ($node) use ($country, &$data) {
        if($node->text() == $country){
            $table = $node->closest('h4')->nextAll()->eq(0);
            $data[] = $table->filter('tbody tr')->each(function($tr, $index){
                if($index == 0){
                    return '{}';
                }
                $tmp = [];
                $tmp['mcc'] = $tr->filter('td')->eq(0)->text();
                $tmp['mnc'] = $tr->filter('td')->eq(1)->text();
                $tmp['brand'] = $tr->filter('td')->eq(2)->text();
                $tmp['operator'] = $tr->filter('td')->eq(3)->text();
                $tmp['status'] = $tr->filter('td')->eq(4)->text();
                $tmp['bands'] = $tr->filter('td')->eq(5)->text();
                return $tmp;
            });
        }
    });
    //print_r($data);
    $data = array_pop($data);
    array_shift($data);
    echo json_encode($data);
}