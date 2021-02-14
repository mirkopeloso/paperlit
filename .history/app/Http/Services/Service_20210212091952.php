<?php

namespace App\Http\Services;

use App\Models\Subscriber;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Hash;
use Log;
use Str;

class Service
{

    public static function parse(array $ss): array
    {

        DB::beginTransaction();
        $subscribers = [];
        foreach ($ss as $s) {

            try {
                $subscriber = new Subscriber();
                if ($subscriber->validate($s)) {

                    $subscriber->publication = $s['publication'];
                    $subscriber->timestamp   = Carbon::createFromFormat('Ymd', $s['timestamp']);
                    $subscriber->user        = $s['user'];
                    $subscriber->name        = $s['name'];
                    $subscriber->start       = Carbon::createFromFormat('Ymd', $s['start']);
                    $subscriber->stop        = Carbon::createFromFormat('Ymd', $s['stop']);
                    $subscribers[]           = $subscriber;

                    $user = User::firstOrCreate(
                        [
                            'id' => $subscriber->user],
                        [
                            'name'              => $subscriber->name,
                            'email'             => Str::lower($subscriber->name) . '@paperlit-test.it',
                            'password'          => Hash::make(Str::lower($subscriber->name)),
                            'email_verified_at' => Carbon::now()->timestamp]);
                    $user->markEmailAsVerified();
                    self::createAbboRangesForSubscriber($subscriber);

                } else {
                    DB::rollback();
                    $errors = $subscriber->errors();
                    return ['status' => 'failed', 'user' => $user, 'errors' => $errors];
                }
            } catch (\Exception $e) {
                DB::rollback();
                return ['status' => 'failed', 'user' => 'generic', 'errors' => [$e->message]];
            }
        }
        DB::commit();
        return ['status' => 'success', 'objects' => User::all()];
    }

    private static function createAbboRangesForSubscriber(Subscriber $subscriber)
    {
        $startDay = $subscriber->start;

        Log::debug('========================== User ' . $subscriber->user);
        Log::debug('========================== Start ' . $subscriber->start);
        Log::debug('========================== Stop ' . $subscriber->stop);

        while ($startDay->diffInDays($subscriber->stop, false) >= 0) {

            Log::debug('diff ' . $startDay->diffInDays($subscriber->stop, false));

            if ($startDay->diffInDays($subscriber->stop, false) > 3650 || ) {
                Log::debug('diff too high' . $subscriber->stop->diffInDays($startDay, true));
                throw new \Exception('Subscription interval is too big.');
            }

            if ($startDay->diffInDays($subscriber->stop, false) / 365 >= 1) {
                $stopDay = $startDay->copy();
                $stopDay->addDays(365);
                $subscription = self::createAbboSubscription($subscription->user, $startDay, $stopDay, 365);
                $startDay     = $stopDay->addDays(1);

            } else
            if ($startDay->diffInDays($subscriber->stop, false) / 30 >= 1) {
                $stopDay = $startDay->copy();
                $stopDay->addDays(30);
                $subscription = self::createAbboSubscription($subscription->user, $startDay, $stopDay, 30);
                $startDay     = $stopDay->addDays(1);

            } else
            if ($startDay->diffInDays($subscriber->stop, false) / 7 >= 1) {
                $stopDay = $startDay->copy();
                $stopDay->addDays(7);
                $subscription = self::createAbboSubscription($subscription->user, $startDay, $stopDay, 7);
                $startDay     = $stopDay->addDays(1);

            } else
            if ($startDay->diffInDays($subscriber->stop, false) / 1 >= 0) {
                $stopDay = $startDay->copy();
                $stopDay->addDays(1);
                $subscription = self::createAbboSubscription($subscription->user, $startDay, $stopDay, 1);
                $startDay     = $stopDay->addDays(1);

            }
        }

    }

    private static function createAbboSubscription(int $user_id, Carbon $startDay, Carbon $stopDay, int $days): Subscription
    {
        $subscription          = new Subscription();
        $subscription->type    = 'abbo-' . days;
        $subscription->start   = $startDay->format('Ymd');
        $subscription->stop    = $stopDay->format('Ymd');
        $subscription->user_id = $user_id;
        $subscription->save();
        return $subscription;
    }

}
