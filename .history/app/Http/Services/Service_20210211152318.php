<?php

namespace App\Http\Services;

use Hash;
use Log;
use Carbon\Carbon;
use App\Models\Subscriber;
use App\Models\Subscription;
use App\Models\User;

class Service
{

    public static function parse(array $ss): array
    {
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
                $user                    = User::firstOrCreate(
                    [
                        'id' => $subscriber->user],
                    [
                        'name'              => $subscriber->name,
                        'email'             => Str::lower($subscriber->name) . '@paperlit-test.it',
                        'password'          => Hash::make(Str::lower($subscriber->name)),
                        'email_verified_at' => Carbon::now()->timestamp]);
                $user->markEmailAsVerified();

            } else {
                $errors = $subscriber->errors();
                return ['status' => 'failed', 'user' => $user, 'errors' => $errors];
            }
        }

        foreach ($subscribers as $subscriber) {
            $startDay = $subscriber->start;

            Log::debug('========================== User ' . $subscriber->user);
            Log::debug('========================== Start ' . $subscriber->start);
            Log::debug('========================== Stop ' . $subscriber->stop);

            while ($startDay->diffInDays($subscriber->stop, false) >= 0) {

                Log::debug('diff ' . $startDay->diffInDays($subscriber->stop, false));

                if ($startDay->diffInDays($subscriber->stop, false) > 3650) {
                    Log::debug('diff too high' . $subscriber->stop->diffInDays($startDay, true));
                    die();
                }

                if ($startDay->diffInDays($subscriber->stop, false) / 365 >= 1) {
                    $stopDay = $startDay->copy();
                    $stopDay->addDays(365);
                    $subscription        = new Subscription();
                    $subscription->type  = 'abbo-365';
                    $subscription->start = $startDay->format('Ymd');
                    $subscription->stop  = $stopDay->format('Ymd');

                    Log::debug('Abbo 365 ' . $subscription->start . '/' . $subscription->stop);

                    $subscriber->subscriptions[] = $subscription;
                    $startDay                    = $stopDay->addDays(1);

                } else
                if ($startDay->diffInDays($subscriber->stop, false) / 30 >= 1) {
                    $stopDay = $startDay->copy();
                    $stopDay->addDays(30);
                    $subscription        = new Subscription();
                    $subscription->type  = 'abbo-30';
                    $subscription->start = $startDay->format('Ymd');
                    $subscription->stop  = $stopDay->format('Ymd');
                    Log::debug('Abbo 030 ' . $subscription->start . '/' . $subscription->stop);
                    $subscriber->subscriptions[] = $subscription;
                    $startDay                    = $stopDay->addDays(1);

                } else
                if ($startDay->diffInDays($subscriber->stop, false) / 7 >= 1) {
                    $stopDay = $startDay->copy();
                    $stopDay->addDays(7);
                    $subscription        = new Subscription();
                    $subscription->type  = 'abbo-7';
                    $subscription->start = $startDay->format('Ymd');
                    $subscription->stop  = $stopDay->format('Ymd');
                    Log::debug('Abbo 007 ' . $subscription->start . '/' . $subscription->stop);
                    $subscriber->subscriptions[] = $subscription;
                    $startDay                    = $stopDay->addDays(1);

                } else
                if ($startDay->diffInDays($subscriber->stop, false) / 1 >= 0) {
                    $stopDay = $startDay->copy();
                    $stopDay->addDays(1);
                    $subscription        = new Subscription();
                    $subscription->type  = 'abbo-1';
                    $subscription->start = $startDay->format('Ymd');
                    $subscription->stop  = $stopDay->format('Ymd');
                    Log::debug('Abbo 001 ' . $subscription->start . '/' . $subscription->stop);
                    $subscriber->subscriptions[] = $subscription;
                    $startDay                    = $stopDay->addDays(1);

                }
            }
        }

        return ['status' => 'success', 'objects' => $subscribers];
    }

}
