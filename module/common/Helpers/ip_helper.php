<?php

function getUserIP(): string
{
    $real_ip_adress = '';

    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $real_ip_adress = $_SERVER['HTTP_CLIENT_IP'];
    }

    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $real_ip_adress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $real_ip_adress = $_SERVER['REMOTE_ADDR'];
    }

    return $real_ip_adress;
}


function getCountryByIp($ip): array

{
    try {
        $xml = file_get_contents(
            "http://www.geoplugin.net/json.gp?ip=" . $ip);

    } catch (Exception $exception) {
        $xml = null;

    }

    if (isset($xml)) {
        $ipdat = @json_decode($xml);
    } else {
        $xml = null;
    }


//		echo 'Country Name: ' . $ipdat->geoplugin_countryName . "\n";
//		echo 'City Name: ' . $ipdat->geoplugin_city . "\n";
//		echo 'Continent Name: ' . $ipdat->geoplugin_continentName . "\n";
//		echo 'Latitude: ' . $ipdat->geoplugin_latitude . "\n";
//		echo 'Longitude: ' . $ipdat->geoplugin_longitude . "\n";
//		echo 'Currency Symbol: ' . $ipdat->geoplugin_currencySymbol . "\n";
//		echo 'Currency Code: ' . $ipdat->geoplugin_currencyCode . "\n";
//		echo 'Timezone: ' . $ipdat->geoplugin_timezone;


    if ($xml != null and isset($ipdat->geoplugin_countryName)) {
        return array('country' => $ipdat->geoplugin_countryName,
            'code' => $ipdat->geoplugin_currencyCode ?? 'n/a',
            'city' => $ipdat->geoplugin_city ? $ipdat->geoplugin_city:'n/a',
            'lat' => $ipdat->geoplugin_latitude ??'n/a',
            'lang' => $ipdat->geoplugin_longitude??'n/a', 'flag' => true);
    } else {
        return array(
            'country' => 'n/a',
            'code' => 'n/a',
            'city' => 'n/a',
            'lat' => 'n/a',
            'lang' => 'n/a',
            'flag' => false);

    }

}
