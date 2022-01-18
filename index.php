<?php

include "vendor/autoload.php";
use Goutte\Client;

$client = new Client();

$crawler = $client->request('GET', 'http://en.wikipedia.org/wiki/Mobile_Network_Code');

$data = [];
$crawler->filter('.sortable tbody tr')->each(function ($tr) use (&$data){
    $tmp = [];
    $response =  $tr->filter('td')->each(function ($td, $i) use (&$tmp) {
        if ($i >3){
            return false;
        }
        
        if ($i == 0) {
            $tmp['code'] = $td->text();
        }
        if ($i == 1) {
            try {
                $tmp['country'] = $td->filter('a')->eq(0)->text();
            } catch (Exception $ex){
                return false;
            }
        }
        if ($i == 3){
            try {
                $tmp['link'] = $td->filter('a')->eq(0)->attr('href');
            } catch (Exception $ex){
                return false;
            }
        }
        return $tmp;
    });
    if(!$response){
        return;
    }
    
    $data[] = $tmp;
    return $data;
    
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Andrey Mussatov Test</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="js/index.js"></script>
</head>
<body>
<select id="country">
    <option value="0">Choose Country</option>
    <?php
    foreach($data as $row){
        if(!array_key_exists('link', $row)){
            continue;
        }
        echo "<option value='{$row["code"]}' data-link='{$row['link']}'>{$row['country']}</option>";
    }
    ?>
</select>

<select id="network">
    <option value="0">Choose Network</option>
</select>
<br><br>
<table border="1">
    <thead>
        <tr>
            <th>Code</th>
            <th>Country</th>
            <th>MCC</th>
            <th>MNC</th>
            <th>Brand</th>
            <th>Operator</th>
            <th>Status</th>
            <th>Bands</th>
        </tr>
    </thead>
    <tbody id="data">
    
    </tbody>
</table>
</body>
</html>

