<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileUploadRequest;
use App\Models\Subscriber;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function fileUpload(FileUploadRequest $request)
    {

        Log::debug('======================================= UPLOAD STARTED');
        Log::debug('======================================= UPLOAD STARTED');
        Log::debug('======================================= UPLOAD STARTED');
        Log::debug('======================================= UPLOAD STARTED');
        Log::debug('======================================= UPLOAD STARTED');
        Log::debug('======================================= UPLOAD STARTED');
        Log::debug('======================================= UPLOAD STARTED');
        Log::debug('======================================= UPLOAD STARTED');

        if ($request->file()) {
            $content = $request->file('file')->get();

            $xml       = simplexml_load_string($content);
            $xml_json  = json_encode($xml);
            $xml_array = json_decode($xml_json, true);

            $ss          = $xml_array['subscriber'];
            $subscribers = [];
            foreach ($ss as $s) {

                $subscriber = new Subscriber();
                if ($subscriber->validate($s)) {

                    $subscriber->publication = $s['publication'];
                    $subscriber->timestamp   = Carbon::createFromFormat('Ymd', $s['timestamp']);
                    $subscriber->user        = $s['user'];
                    $subscriber->name        = $s['name'];
                    $subscriber->start       = Carbon::createFromFormat('Ymd', $s['start']);
                    $subscriber->stop        = Carbon::createFromFormat('Ymd', $s['stop']);
                    $subscribers[]           = $subscriber;
                } else {
                    $errors = $subscriber->errors();
                    return back()->with('errorUser', json_encode($s))
                        ->withErrors($errors);
                }
            }

            foreach ($subscribers as $subscriber) {
                $startDay = $subscriber->start;

                Log::debug('========================== User ' . $subscriber->user);
                Log::debug('========================== Start ' . $subscriber->start);
                Log::debug('========================== Stop ' . $subscriber->stop);

                while ($startDay >$subscriber->stop->diffInDays() >1) {

                    Log::debug('diff ' . $subscriber->stop->diffInDays($startDay, false));

                    if ($subscriber->stop->diffInDays($startDay) > 3650) {
                        Log::debug('diff too high' . $subscriber->stop->diffInDays($startDay, true));
                        die();
                    }

                    if ($subscriber->stop->diffInDays($startDay, false) / 365 > 1) {
                        Log::debug('entered in 365, startday is ' . $startDay);
                        $stopDay = $startDay->addDays(365);
                        Log::debug('entered in 365, stopDay is ' . $stopDay);
                        $subscription                = new Subscription();
                        $subscription->type          = 'abbo-365';
                        $subscription->start         = $startDay;
                        $subscription->stop          = $stopDay;
                        $subscriber->subscriptions[] = $subscription;
                        $startDay                    = $stopDay->addDays(1);
                        Log::debug('---> new startday is ' . $startDay);

                    } else
                    if ($subscriber->stop->diffInDays($startDay, false) / 30 > 1) {
                        Log::debug('entered in 30');
                        $stopDay                     = $startDay->addDays(30);
                        $subscription                = new Subscription();
                        $subscription->type          = 'abbo-30';
                        $subscription->start         = $startDay;
                        $subscription->stop          = $stopDay;
                        $subscriber->subscriptions[] = $subscription;
                        $startDay                    = $stopDay->addDays(1);

                    } else
                    if ($subscriber->stop->diffInDays($startDay, false) / 7 > 1) {
                        Log::debug('entered in 7');
                        $stopDay                     = $startDay->addDays(7);
                        $subscription                = new Subscription();
                        $subscription->type          = 'abbo-7';
                        $subscription->start         = $startDay;
                        $subscription->stop          = $stopDay;
                        $subscriber->subscriptions[] = $subscription;
                        $startDay                    = $stopDay->addDays(1);

                    } else
                    if ($subscriber->stop->diffInDays($startDay, false) / 1 > 1) {
                        Log::debug('entered in 1');
                        $stopDay                     = $startDay->addDays(1);
                        $subscription                = new Subscription();
                        $subscription->type          = 'abbo-1';
                        $subscription->start         = $startDay;
                        $subscription->stop          = $stopDay;
                        $subscriber->subscriptions[] = $subscription;
                        $startDay                    = $stopDay->addDays(1);

                    } else {
                        Log::debug('DEAD');
                        die();
                    }
                }
            }

            return back()
                ->with('success', 'File has been uploaded.')
                ->with('result', "{empty json}");
        }
    }
}
