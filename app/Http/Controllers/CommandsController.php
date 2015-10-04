<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class CommandsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        dd('commands test');
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

        $sNagiosRootDir   =   config('nagios.objects_dir');


        $sFile = "{$sNagiosRootDir}/commands.cfg";

        if (file_exists($sFile)) {
            unlink($sFile);
        }

        if(isset($_POST['payload'])){
            $aPayload   =   json_decode($_POST['payload'],true);
            $sContents  =   "";

            //dd($aPayload);
            foreach($aPayload as $k => $v){

                $sDetail    =   "";

                foreach($v['details'] as $kDetail => $vDetail){
                        $sDetail    .=  "\t{$kDetail}\t{$vDetail}\n";
                }

                $sContents  .=   "define command{\n{$sDetail}}\n";

            }

            //echo $sContents;


            if(file_put_contents($sFile, $sContents, FILE_APPEND | LOCK_EX)){
/*
                $myfile = fopen($sFile, "r") or die("Unable to open file!");
                echo fread($myfile,filesize($sFile));
                fclose($myfile);
*/

                return (new Response(json_encode(['msg'=>'File writing success']),200))->header('Content-Type', "application/json");

            }else{

                return (new Response(json_encode(['msg'=>'File writing fail']),400))->header('Content-Type', "application/json");

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
