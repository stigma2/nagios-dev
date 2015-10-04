<?php

namespace App\Utils;

use App\Interfaces\NagiosInterface;

class Nagios implements NagiosInterface
{

    public function getCgiResult($sCommand)
    {
        $username   =   config('nagios.username');
        $password   =   config('nagios.password');
        $sDomain    =   config('nagios.domain');

        $sUrl   =    "{$sDomain}{$sCommand}";

        //dd($sUrl);


        $nPort  =   80;
        $nTimeout   =   3;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $sUrl);
        curl_setopt($ch, CURLOPT_PORT ,  $nPort);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_COOKIE,  '');
        curl_setopt($ch, CURLOPT_USERPWD,"$username:$password");
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750");
        curl_setopt($ch, CURLOPT_TIMEOUT, $nTimeout);
        $data = curl_exec($ch);

        $curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);

        return $data;
    }

}
