<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class StatisticController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

        if($id == 'host'){
            $sCommand   =    "/nagios/cgi-bin/statusjson.cgi?query=hostcount&hoststatus=up+down+unreachable+pending";
            $aResult    =   json_decode($this->getCgiResult($sCommand),true);

            $aResult['data']['count']['problems']   = $aResult['data']['count']['unreachable'];
            $aResult['data']['count']['types']      = array_sum($aResult['data']['count']);

        }else if($id == 'service'){

            $sCommand   =    "/nagios/cgi-bin/statusjson.cgi?query=servicecount&servicestatus=ok+warning+critical+unknown+pending";
            $aResult    =   json_decode($this->getCgiResult($sCommand),true);

            $aResult['data']['count']['problems']   = $aResult['data']['count']['critical'] + $aResult['data']['count']['unknown'];
            $aResult['data']['count']['types']      = array_sum($aResult['data']['count']);

        }else if($id == 'log'){

            if(isset($_GET['n'])){
                $nN =   $_GET['n'];
            }else{
                $nN =    10;
            }

            $sCommand           =   "tail -n{$nN} /usr/local/nagios/var/nagios.log 2>&1";

            $aOutput            =   [];
            $nReturn            =   0;

            exec($sCommand, $aOutput, $nReturn);

            dd($aOutput);
        }else {

            return (new Response(json_encode(['msg'=>'Invalid argument']),400))->header('Content-Type', "application/json");

        }

        return response()->json($aResult);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

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
