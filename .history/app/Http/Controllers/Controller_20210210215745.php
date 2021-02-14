<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Requests\FileUploadRequest;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function fileUpload(FileUploadRequest $request){

        if($request->file()) {
            $content = $request->file('file')->get();

            $xml = simplexml_load_string($content);
            $xml_json = json_encode($xml);
            $xml_array = json_decode($xml_json,TRUE);

            $subscribers=$xml_array['subscriber'] ;
             foreach($subscribers as $s){
                $subscriber=new Subscription();
                $subscriber->publication = $s['publication'];
                $subscriber->timestamp = $s['timestamp'];
                $subscriber->user = $s['user'];
                $subscriber->publication = $s['publication'];
                $subscriber->publication = $s['publication'];
                'timestamp' => string '20200810' (length=8)
                'user' => string '410343867' (length=9)
                'name' => string 'BONINI' (length=6)
                'start' => string '20200718' (length=8)
                'stop' => string '20210717' (length=8)


                 var_dump($subscriber);
             }







            die();

            return back()
            ->with('success','File has been uploaded.')
            ->with('result', "{empty json}");
        }
   }
}
