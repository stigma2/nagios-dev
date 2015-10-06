<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Utils\Nagios;


class ServiceController extends Controller
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


        if(isset($_GET['servicestatus'])){

            $sStatus    =   $_GET['servicestatus'];
            $sCommand   =    "/nagios/cgi-bin/statusjson.cgi?query=servicelist&details=true&servicestatus={$sStatus}";

        }else{

            $sCommand   =    "/nagios/cgi-bin/statusjson.cgi?query=servicelist&details=true";

        }

        $aResult    =   json_decode($this->utils->getCgiResult($sCommand));

        return response()->json($aResult);




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

        //http://106.243.134.121:22180/nagios_dev/api/v1/hosts


        $sServerDir   =   config('nagios.servers_dir');
        $sObjectDir   =   config('nagios.objects_dir');

        $sFileY = "{$sObjectDir}/templates.cfg";
        $sFileN = "{$sServerDir}/services.cfg";

        if (file_exists($sFileY)) {
            unlink($sFileY);
        }

        if (file_exists($sFileN)) {
            unlink($sFileN);
        }



        if(isset($_POST['payload'])){
            $aPayload   =   json_decode($_POST['payload'],true);
            $sContentsY  =   "";
            $sContentsN  =   "";

            foreach($aPayload as $k => $v){

                $sDetail    =  "";
                foreach($v['details'] as $kDetail => $vDetail){
                    $sDetail    .=  "\t{$kDetail}\t{$vDetail}\n";
                }

                if($v['is_template'] == 'Y'){

                    $sContentsY  .=   "define host{\n{$sDetail}}\n";

                }else{

                    $sContentsN  .=   "define host{\n{$sDetail}}\n";

                }


            }

            //echo $sContents;

            $isTemplateY    =   file_put_contents($sFileY, $sContentsY, FILE_APPEND | LOCK_EX);
            $isTemplateN    =   file_put_contents($sFileN, $sContentsN, FILE_APPEND | LOCK_EX);



            if($isTemplateY && $isTemplateN){


                return (new Response(json_encode(['msg'=>'File writing success']),200))->header('Content-Type', "application/json");

            }else{

                $sError =   '';
                if(!$isTemplateY) $sError   .=   'templates.cfg ';
                if(!$isTemplateN) $sError   .=   'hosts.cfg ';
                return (new Response(json_encode(['msg'=>"{$sError} File writing fail"]),400))->header('Content-Type', "application/json");

            }

        }else{

            return (new Response(json_encode(['msg'=>'Invalid argument']),400))->header('Content-Type', "application/json");

        }


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

        if(isset($id) && isset($_GET['servicedescription'])){
            $sHostName            =   $id;
            $sServiceDescription  =   urlencode($_GET['servicedescription']);

            $sCommand   =    "/nagios/cgi-bin/statusjson.cgi?query=service&hostname={$sHostName}&servicedescription={$sServiceDescription}";

            $bSuccess   =   true;
            $aResult    =   json_decode($this->utils->getCgiResult($sCommand));

        }else{

            $bSuccess   =   false;
            $aResult    =   "Bad Request";
        }


        //return response()->json(['success' => $bSuccess, 'result' => $aResult]);
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
