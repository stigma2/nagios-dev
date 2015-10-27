<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Utils\Nagios;
use Illuminate\Http\Response;

class StatisticController extends Controller
{

    private $utils;

    public function __construct()
    {
        $this->utils = new Nagios();
    }

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
        //http://106.243.134.121:9090/nagios/cgi-bin/archivejson.cgi?query=alertlist&objecttypes=host&starttime=1424289217&endtime=1444289217

        if($id == 'host'){
            $sCommand   =    "/nagios/cgi-bin/statusjson.cgi?query=hostcount&hoststatus=up+down+unreachable+pending";
            $aResult    =   json_decode($this->utils->getCgiResult($sCommand),true);

            $aResult['data']['count']['types']      = array_sum($aResult['data']['count']);
            $aResult['data']['count']['problems']   = $aResult['data']['count']['unreachable'] + $aResult['data']['count']['down'];

        }else if($id == 'service'){

            $sCommand   =    "/nagios/cgi-bin/statusjson.cgi?query=servicecount&servicestatus=ok+warning+critical+unknown+pending";
            $aResult    =   json_decode($this->utils->getCgiResult($sCommand),true);

            $aResult['data']['count']['types']      = array_sum($aResult['data']['count']);
            $aResult['data']['count']['problems']   = $aResult['data']['count']['critical'] + $aResult['data']['count']['unknown'] + $aResult['data']['count']['warning'];

        }else if($id == 'log'){

            if(isset($_GET['type']) && isset($_GET['starttime']) && isset($_GET['endtime']) ){
                //http://106.243.134.121:9090/nagios/cgi-bin/archivejson.cgi?query=alertlist&objecttypes=host&starttime=1424289217&endtime=1444289217
                $sCommand   =    "/nagios/cgi-bin/archivejson.cgi?query=alertlist&objecttypes={$_GET['type']}&starttime={$_GET['starttime']}&endtime={$_GET['endtime']}";
                $aResult    =   json_decode($this->utils->getCgiResult($sCommand),true);
                //dd($sCommand);
                //dd($this->utils->getCgiResult($sCommand));
            }else{

                return (new Response(json_encode(['msg'=>'Invalid argument']),400))->header('Content-Type', "application/json");

            }




        }else {

            return (new Response(json_encode(['msg'=>'Invalid argument']),400))->header('Content-Type', "application/json");

        }

        //echo "test";
        //dd($_GET);
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



}
