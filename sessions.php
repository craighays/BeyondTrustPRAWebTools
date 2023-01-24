<?php

$tokenUrl = "https://<SERVER>.beyondtrustcloud.com/oauth2/token";
$BasicAuth = "<CLIENT_ID>:<CLIENT_SECRET>";
$BasicAuthEncoded  = base64_encode($BasicAuth);
$authorization = "Authorization: Basic ".$BasicAuthEncoded;

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,$tokenUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array($authorization ));
curl_setopt($ch, CURLOPT_POSTFIELDS,
            "grant_type=client_credentials");

// In real life you should use something like:
// curl_setopt($ch, CURLOPT_POSTFIELDS, 
//          http_build_query(array('postvar1' => 'value1')));

// Receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($ch);

curl_close($ch);

$data = json_decode($server_output);

$AuthBearer = "Authorization: Bearer ".$data->access_token;


$now = time();
$reportFrom = $now - (60 * 60 * 24 * 7); // 60 seconds x 60 minutes x 24 hours x 1 days = 1 days ago in seconds
$APIRequest = "https://<SERVER>.beyondtrustcloud.com/api/reporting?generate_report=AccessSession&start_time=".$reportFrom."&duration=0";

//echo($AuthBearer);
//echo($APIRequest);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,$APIRequest);
curl_setopt($ch, CURLOPT_POST, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, array($AuthBearer));

// In real life you should use something like:
// curl_setopt($ch, CURLOPT_POSTFIELDS,
//          http_build_query(array('postvar1' => 'value1')));

// Receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($ch);
//echo($server_output);

curl_close($ch);


$xml = simplexml_load_string($server_output);
header('Content-Type: text/html');
echo("<!DOCTYPE html><html>");?>
<head>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>

<?php
echo('<body>');
date_default_timezone_set('Europe/London');
$current_time = date('m/d/Y h:i:s a', time());
echo('<p style="padding:10px 0 0 10px">7 days of sessions. Last updated at '.$current_time.'</p>');
echo('<table class="table">');
echo("<tr><th>Start Time</th><th>End Time</th><th>Hostname</th><th>OS</th><th>Internal IP</th><th>Username</th><th>User External IP</th><th>User Internal IP</th><th>User Device</th><th>User Device OS</th>");
foreach ($xml->session as $session){


    if($session->end_time == "") {
        $end_time = "Active";
        echo('<tr>');
    $start_time = DateTime::createFromFormat(DateTime::ISO8601,$session->start_time);
    if($end_time != "Active"){
        $end_time = DateTime::createFromFormat(DateTime::ISO8601,$session->end_time);
    }
    echo "<td>". $start_time->format('Y-m-d H:i:s') ."</td>";
    if($end_time != "Active"){
        echo "<td>". $end_time->format('Y-m-d H:i:s') ."</td>";
    } else {
        echo "<td>$end_time</td>";
    }
    foreach($session->customer_list as $customer_item){
        foreach($customer_item as $customer){
        echo "<td>$customer->hostname</td>";
        echo "<td>$customer->os</td>";
        echo "<td>$customer->private_ip</td>";
        }
    }
    foreach($session->rep_list as $rep_item){
        foreach($rep_item as $rep){
        echo "<td>$rep->username</td>";
        $public_ip = explode(":", $rep->public_ip);
        echo "<td>".$public_ip[0]."</td>";
        echo "<td>$rep->private_ip</td>";
        echo "<td>$rep->hostname</td>";
        echo "<td>$rep->os</td>";
        }
    }



    echo("</tr>");

    } 
    
}

foreach ($xml->session as $session){


    if($session->end_time != "") {
        $end_time = $session->end_time;
        echo('<tr class="table-dark">');
    $start_time = DateTime::createFromFormat(DateTime::ISO8601,$session->start_time);
    if($end_time != "Active"){
        $end_time = DateTime::createFromFormat(DateTime::ISO8601,$session->end_time);
    }
    echo "<td>". $start_time->format('Y-m-d H:i:s') ."</td>";
    if($end_time != "Active"){
        echo "<td>". $end_time->format('Y-m-d H:i:s') ."</td>";
    } else {
        echo "<td>$end_time</td>";
    }
    foreach($session->customer_list as $customer_item){
        foreach($customer_item as $customer){
        echo "<td>$customer->hostname</td>";
        echo "<td>$customer->os</td>";
        echo "<td>$customer->private_ip</td>";
        }
    }
    foreach($session->rep_list as $rep_item){
        foreach($rep_item as $rep){
        echo "<td>$rep->username</td>";
        $public_ip = explode(":", $rep->public_ip);
        echo "<td>".$public_ip[0]."</td>";
        echo "<td>$rep->private_ip</td>";
        echo "<td>$rep->hostname</td>";
        echo "<td>$rep->os</td>";
        }
    }



    echo("</tr>");

    }     
}

echo("</table>");
echo("</body>");
echo("</html>");

?>
