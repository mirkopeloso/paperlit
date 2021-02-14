<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileUploadRequest;
use App\Models\User;
use App\Models\Subscriber;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function fileUpload(FileUploadRequest $request)
    {

        if ($request->file()) {
            $content = $request->file('file')->get();

            $xml       = simplexml_load_string($content);
            $xml_json  = json_encode($xml);
            $xml_array = json_decode($xml_json, true);

            $ss = $xml_array['subscriber'];
            $subscribers=[];
            foreach ($ss as $s) {

                $subscriber = new Subscriber();
                if ($subscriber->validate($s)) {

                    $subscriber->publication = $s['publication'];
                    $subscriber->timestamp   = Carbon::parse($s['timestamp'])->format('Ymd');
                    $subscriber->user        = $s['user'];
                    $subscriber->name        = $s['name'];
                    $subscriber->start       = Carbon::parse($s['start'])->format('Ymd');
                    $subscriber->stop        = Carbon::parse($s['stop'])->format('Ymd');
                    $subscribers []=$subscriber;
                }
                else{
                    $errors = $subscriber->errors();
                    return back()->with('errorUser', json_encode($s))
                    ->withErrors($errors);
                }
            }

            foreach ($subscribers as $subscriber) {
                $remaining=$subscriber->stop->diffInDays($subscriber->start);
                $startDay = $subscriber->start;
                while($remaining>0){

                    if($remaining%365>0)
                    {

                        $stopDay=
                        $subscription=new Subscription();
                        $subscription->type='abbo-365';
                        $subscription->start=$startDay;
                        $subscription->stop=
                        $subscriber->subscriptions[]= $subscription;
                        $startDay->addDays(365);
                        $remaining=$subscriber->stop->diffInDays($startDay);
                    }

                    $remaining=0;

                }
            }



            return back()
                ->with('success', 'File has been uploaded.')
                ->with('result', "{empty json}");
        }
    }
}
