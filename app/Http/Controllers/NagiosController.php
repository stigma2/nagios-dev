<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class NagiosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        if(isset($_GET['command'])){

            $sParamCommand      =   $_GET['command'];
            $sCommand           =   "sudo service nagios {$sParamCommand} 2>&1";

            $aOutput            =   [];
            $nReturn            =   0;

            exec($sCommand, $aOutput, $nReturn);

            //dd($aOutput);

            if($sParamCommand == 'restart'){

                if(count($aOutput)==3 && $aOutput[2] == "Starting nagios: done."){

                    return (new Response(json_encode(['msg'=>'Restart success']),200))->header('Content-Type', "application/json");


                }else{

                    return (new Response(json_encode(['msg'=>'Restart fail','error'=>$aOutput]),400))->header('Content-Type', "application/json");

                }


            }else if($sParamCommand == 'status'){

                if(count($aOutput) == 1  && strstr($aOutput[0],"running") != false) {

                    return (new Response(json_encode(['msg'=>'Nagios is running']),200))->header('Content-Type', "application/json");

                }else{

                    return (new Response(json_encode(['msg'=>'Nagios is not running','error'=>$aOutput]),400))->header('Content-Type', "application/json");

                }

            }else{

                return (new Response(json_encode(['msg'=>'Can not execute the command']),400))->header('Content-Type', "application/json");

            }

        }else{

            return (new Response(json_encode(['msg'=>'Invalid argument']),400))->header('Content-Type', "application/json");

        }


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
        //dd($_POST);

        if( isset($_POST['username']) && isset($_POST['password']) && isset($_POST['domain']) && isset($_POST['servers_dir']) && isset($_POST['objects_dir']) ){

            $sFile = "/var/www/html/nagios_dev/config/nagios.php";

            if (file_exists($sFile)) {
                unlink($sFile);
            }


            $sContents  =   "
<?php
    return [


        /*
         *      나기오스 로그인 정보
         *
         */
        'username'          => '{$_POST['username']}',
        'password'          => '{$_POST['password']}',
        'domain'            => '{$_POST['domain']}',


        /*
         *      나기오스 디렉토리 정보
         *
         */

        'servers_dir'   =>  '{$_POST['servers_dir']}',    // hosts.conf, services.conf
        'objects_dir'   =>  '{$_POST['objects_dir']}',    // commands.conf

    ];
";

            $isTemplate    =   file_put_contents($sFile, $sContents, FILE_APPEND | LOCK_EX);

            if($isTemplate) {
                return (new Response(json_encode(['msg'=>'File writing success']),200))->header('Content-Type', "application/json");
            }else{
                return (new Response(json_encode(['msg'=>" File writing fail"]),400))->header('Content-Type', "application/json");
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

        $sServerDir   =   config('nagios.servers_dir');
        $sObjectDir   =   config('nagios.objects_dir');

        $sFileY = "{$sObjectDir}/templates.cfg";
        $sFileN = "{$sServerDir}/hosts.cfg";
        $sFile3 = "{$sServerDir}/services.cfg";

        $myfile = fopen($sFileY, "r") or die("Unable to open file!");
        echo "\n\ntemplates.cfg\n\n";
        echo fread($myfile,filesize($sFileY));
        fclose($myfile);

        $myfile = fopen($sFileN, "r") or die("Unable to open file!");
        echo "\n\nhosts.cfg\n\n";
        echo fread($myfile,filesize($sFileN));
        fclose($myfile);

        $myfile = fopen($sFile3, "r") or die("Unable to open file!");
        echo "\n\nservices.cfg\n\n";
        echo fread($myfile,filesize($sFile3));
        fclose($myfile);

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
