<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\User;
use App\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShipmentController extends Controller
{
    public function done(Request $request)
    {
        $ticket = Ticket::where('id', $request->id)->first();
        $ticket->done = $request->done;
        $ticket->save();

        return 'success!!';
    }

    public function fetch(Request $request)
    {
        $thisYear = date('Y');
        $thisMonth = date('n');
        $thisDay = date('j');
        if (26 <= $thisDay || $thisDay <= 9) {
            $judgeTerm = 'first';
        } elseif (11 <= $thisDay &&  $thisDay <= 24) {
            $judgeTerm = 'second';
        } else {
            //１０日と２５日は発送日なので対応しない
        }

        $count = 0;
        $user = User::where('id', $request->userId)->first();
        foreach ($user->shipments as $shipment) {
            if ($thisYear == $shipment->year && $thisMonth == $shipment->month && $judgeTerm == $shipment->term) {
                //テーブル情報があるパターン
                if ($judgeTerm == 'first') {
                    if ($shipment->plan == 'standard' || $shipment->plan == 'free') {
                        //4-2 firstかつstandardでshipmentを加算
                        //6-2 firstかつfreeでshipmentを加算
                        logger('aaa', ['shipment' => $shipment]);
                        logger('aaa', ['plan' => $shipment->plan]);
                        $data = Shipment::where('user_id', $request->userId)->where('plan', $shipment->plan)->where('year', $thisYear)->where('month', $thisMonth)->where('term', $judgeTerm)->first();
                        logger('aaa', ['data' => $data]);
                        $data->shipment = $data->shipment + 1;
                        $data->save();
                        $info = array('status'=> $data->shipment);
                    }
                } elseif ($judgeTerm == 'second') {
                    if ($shipment->plan == 'standard' || $shipment->plan == 'lite' || $shipment->plan == 'free') {
                        //1 secondかつstandardでshipmentを加算
                        //2  secondかつliteでshipmentを加算
                        //3-2  secondかつfreeでshipmentを加算
                        $data = Shipment::where('user_id', $request->userId)->where('plan', $shipment->plan)->where('year', $thisYear)->where('month', $thisMonth)->where('term', $judgeTerm)->first();
                        $data->shipment = $data->shipment + 1;
                        $data->save();
                        $info = array('status'=> $data->shipment);
                    }
                }
                $count++;
                break;
            }
        }
        if ($count == 0) {
            //テーブル情報がないパターン
            if ($judgeTerm == 'first') {
                if ($user->plan == 'free' || $user->plan == 'standard') {
                    //4-1 firstかつstandardでshipmentに1
                    //6-1 firstかつfreeでshipmentに1
                    $shipment = new Shipment();
                    $shipment->user_id = $request->userId;
                    $shipment->plan = $user->plan;
                    $shipment->year = $thisYear;
                    $shipment->month = $thisMonth;
                    $shipment->term = $judgeTerm;
                    $shipment->shipment = 1;
                    $shipment->save();
                    $info = array('status'=> $shipment->shipment);
                } elseif ($user->plan == 'lite') {
                    //5 なにもしない
                    $info = array('status'=> 'none');
                }
            } elseif ($judgeTerm == 'second') {
                if ($user->plan == 'free') {
                    //3-1 secondかつfreeでshipmentに1
                    $shipment = new Shipment();
                    $shipment->user_id = $request->userId;
                    $shipment->plan = $user->plan;
                    $shipment->year = $thisYear;
                    $shipment->month = $thisMonth;
                    $shipment->term = $judgeTerm;
                    $shipment->shipment = 1;
                    $shipment->save();
                    $info = array('status'=> $shipment->shipment);
                }
            }
        }

        return response()->json(['shipment' => $info], 200);
    }
}
