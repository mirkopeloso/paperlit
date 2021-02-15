<?php

namespace App\Http\Services;

use App\Models\Subscriber;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Hash;
use Str;

class Service
{

    /**
     * Undocumented function
     *
     * @param array $ss    the array of parsed xml subscriptions
     * @return array       an associative array with fields for frontend/rest response
     */
    public static function parse(array $ss): array
    {

        DB::beginTransaction();
        foreach ($ss as $s) {
            $subscriber = new Subscriber();
            try {

                if ($subscriber->validate($s)) {

                    $subscriber->publication = $s['publication'];
                    $subscriber->timestamp   = Carbon::createFromFormat('Ymd', $s['timestamp']);
                    $subscriber->user        = $s['user'];
                    $subscriber->name        = $s['name'];
                    $subscriber->start       = Carbon::createFromFormat('Ymd', $s['start']);
                    $subscriber->stop        = Carbon::createFromFormat('Ymd', $s['stop']);

                    $user = User::firstOrCreate(
                        [
                            'id' => $subscriber->user],
                        [
                            'name'              => $subscriber->name,
                            'email'             => Str::lower($subscriber->user) . '@paperlit-test.it',
                            'password'          => Hash::make(Str::lower($subscriber->name)),
                            'email_verified_at' => Carbon::now()->timestamp]);
                    $user->markEmailAsVerified();
                    self::createAbboRangesForSubscriber($subscriber);

                } else {
                    DB::rollback();
                    $errors = $subscriber->errors();
                    return ['status' => 'failed', 'object' => $s, 'errors' => $errors];
                }
            } catch (\Exception $e) {
                DB::rollback();
                Log::error($e);
                return ['status' => 'failed', 'object' => $s, 'errors' => [$e->getMessage()]];
            }
        }
        DB::commit();
        return ['status' => 'success', 'objects' => User::all()];
    }

    /**
     * Creates an user and attached subscription from subscriber object
     *
     * @param Subscriber $subscriber    the subscriber object created from xml parser
     * @return void
     */
    private static function createAbboRangesForSubscriber(Subscriber $subscriber)
    {
        $startDay = $subscriber->start;


        while ($startDay->diffInDays($subscriber->stop, false) >= 0) {


            if ($startDay->diffInDays($subscriber->stop, false) / 365 >= 1) {
                $stopDay = $startDay->copy();
                $stopDay->addDays(365);
                $subscription = self::createAbboSubscription($subscriber->user, $startDay, $stopDay, 365);
                $startDay     = $stopDay->addDays(1);

            } else
            if ($startDay->diffInDays($subscriber->stop, false) / 30 >= 1) {
                $stopDay = $startDay->copy();
                $stopDay->addDays(30);
                $subscription = self::createAbboSubscription($subscriber->user, $startDay, $stopDay, 30);
                $startDay     = $stopDay->addDays(1);

            } else
            if ($startDay->diffInDays($subscriber->stop, false) / 7 >= 1) {
                $stopDay = $startDay->copy();
                $stopDay->addDays(7);
                $subscription = self::createAbboSubscription($subscriber->user, $startDay, $stopDay, 7);
                $startDay     = $stopDay->addDays(1);

            } else
            if ($startDay->diffInDays($subscriber->stop, false) / 1 >= 0) {
                $stopDay = $startDay->copy();
                $stopDay->addDays(1);
                $subscription = self::createAbboSubscription($subscriber->user, $startDay, $stopDay, 1);
                $startDay     = $stopDay->addDays(1);

            }
        }

    }

    /**
     * Create a subscription from a date range
     *
     * @param integer $user_id  the subscription user id
     * @param Carbon $startDay  the subscription start day
     * @param Carbon $stopDay   the subscription start day
     * @param integer $days     the subscription name (days number)
     * @return Subscription
     */
    private static function createAbboSubscription(int $user_id, Carbon $startDay, Carbon $stopDay, int $days): Subscription
    {
        $subscription          = new Subscription();
        $subscription->type    = 'abbo-' . $days;
        $subscription->start   = $startDay->format('Ymd');
        $subscription->stop    = $stopDay->format('Ymd');
        $subscription->user_id = $user_id;
        $subscription->save();
        return $subscription;
    }

}
