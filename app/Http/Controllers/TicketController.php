<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
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
        if ($request->from == '' || $request->to) {
            //
        }
        if ($request->qutteId != '') {
            $ticket = Ticket::where('id', $request->qutteId)->first();
            if (empty($ticket)) {
                $info = array('status'=> 'unknown');
            } else {
                if ($ticket->done == '1') {
                    $info = array('status'=> 'done');
                } else {
                    if ($ticket->used == '1') {
                        $oldFromUser = User::where('id', $ticket->from)->first();
                        $oldToUser = User::where('id', $ticket->to)->first();
                        $fromUser = User::where('id', $request->from)->first();
                        $toUser = User::where('id', $request->to)->first();
                        $info = array('fromUser' => $fromUser->nickname,
                                        'toUser'=> $toUser->nickname,
                                        'fromUserUrl' => '/users/' . $fromUser->name,
                                        'toUserUrl' => '/users/' . $toUser->name,
                                        'fromUserId' => $fromUser->id,
                                        'toUserId'=> $toUser->id,
                                        'fromUserIcon' => $fromUser->profile->icon,
                                        'toUserIcon'=> $toUser->profile->icon,
                                        'oldFromUser'=> $oldFromUser->nickname,
                                        'oldToUser'=> $oldToUser->nickname,
                                        'oldFromUserUrl' => '/users/' . $oldFromUser->name,
                                        'oldToUserUrl' => '/users/' . $oldToUser->name,
                                        'oldFromUserId'=> $oldFromUser->id,
                                        'oldToUserId'=> $oldToUser->id,
                                        'oldFromUserIcon' => $oldFromUser->profile->icon,
                                        'oldToUserIcon'=> $oldToUser->profile->icon,
                                        'status'=> 'override'
                                    );
                        $ticket->from = $request->from;
                        $ticket->to = $request->to;
                        $ticket->used = '1';
                        $ticket->save();
                    } else {
                        $ticket->from = $request->from;
                        $ticket->to = $request->to;
                        $ticket->used = '1';
                        $ticket->save();
                        $fromUser = User::where('id', $request->from)->first();
                        $toUser = User::where('id', $request->to)->first();
                        $info = array('fromUser' => $fromUser->nickname,
                                        'toUser'=> $toUser->nickname,
                                        'fromUserUrl' => '/users/' . $fromUser->name,
                                        'toUserUrl' => '/users/' . $toUser->name,
                                        'fromUserId' => $fromUser->id,
                                        'toUserId'=> $toUser->id,
                                        'fromUserIcon' => $fromUser->profile->icon,
                                        'toUserIcon'=> $toUser->profile->icon,
                                        'status'=> 'new'
                                    );
                    }
                }
            }
        }

        return response()->json(['qutteInfo' => $info], 200);
    }

    public function bottleFetch(Request $request)
    {
        if ($request->from == '') {
            //
        }
        if ($request->qutteId != '') {
            $ticket = Ticket::where('id', $request->qutteId)->first();
            if (empty($ticket)) {
                $info = array('status'=> 'unknown');
            } else {
                if ($ticket->done == '1') {
                    $info = array('status'=> 'done');
                } else {
                    $fromUser = User::where('id', $request->from)->first();
                    $fromGender = $fromUser->profile->gender;
                    $fromCanSendGender = $fromUser->profile->canSendGender;
                    $fromId = $fromUser->id;

                    $query = User::query();
                    $query->leftJoin('profiles', function ($query) {
                        $query->on('profiles.user_id', 'users.id');
                    });


                    $query->where(function ($query) use ($fromId) {
                        return $query->where('users.id', '<>', $fromId);
                    });
                    if ($fromGender == 9) {
                        $query->where(function ($query) use ($fromGender) {
                            return $query->where('profiles.canSendGender', $fromGender);
                        });
                    } else {
                        $query->where(function ($query) use ($fromGender) {
                            return $query->orWhere('profiles.canSendGender', $fromGender)->orWhere('profiles.canSendGender', '9');
                        });
                    }
                    if ($fromCanSendGender == 9) {
                        //何もしない
                    } else {
                        $query->where(function ($query) use ($fromCanSendGender) {
                            return $query->orWhere('profiles.gender', $fromCanSendGender);
                        });
                    }


                    $query->whereNotIn('users.id', function ($query) use ($fromId) {
                        return $query->select('blockee_id')->from('blocks')->where('blocker_id', $fromId)->whereNotNull('blockee_id');
                    });
                    $query->whereNotIn('users.id', function ($query) use ($fromId) {
                        return $query->select('blocker_id')->from('blocks')->where('blockee_id', $fromId)->whereNotNull('blocker_id');
                    });


                    $query->whereNotIn('users.id', function ($query) use ($fromId) {
                        return $query->select('from')->from('tickets')->where('to', $fromId)->whereNotNull('from');
                    });
                    $query->whereNotIn('users.id', function ($query) use ($fromId) {
                        return $query->select('to')->from('tickets')->where('from', $fromId)->whereNotNull('to');
                    });

                    $query->where(function ($query) use ($fromGender) {
                        return $query->where('profiles.status', true);
                    });

                    $query->where(function ($query) {
                        return $query->where('profiles.zipcode1', '<>', '')
                                        ->where('profiles.zipcode2', '<>', '')
                                        ->where('profiles.pref', '<>', '')
                                        ->where('profiles.address1', '<>', '')
                                        ->where('profiles.address2', '<>', '')
                                        ->where('profiles.realname', '<>', '');
                    });

                    $query->where(function ($query) {
                        return $query->where('users.plan', '<>', 'free');
                    });

                    $toUser = $query->inRandomOrder()->first(['users.id','users.nickname','users.name','profiles.icon']);

                    if (empty($toUser)) {
                        $info = array('status'=> 'none');
                    } else {
                        if ($ticket->used == '1') {
                            $oldFromUser = User::where('id', $ticket->from)->first();
                            $oldToUser = User::where('id', $ticket->to)->first();
                            $info = array('fromUser' => $fromUser->nickname,
                                            'toUser'=> $toUser->nickname,
                                            'fromUserUrl' => '/users/' . $fromUser->name,
                                            'toUserUrl' => '/users/' . $toUser->name,
                                            'fromUserId' => $fromUser->id,
                                            'toUserId'=> $toUser->id,
                                            'fromUserIcon' => $fromUser->profile->icon,
                                            'toUserIcon'=> $toUser->icon,
                                            'oldFromUser'=> $oldFromUser->nickname,
                                            'oldToUser'=> $oldToUser->nickname,
                                            'oldFromUserUrl' => '/users/' . $oldFromUser->name,
                                            'oldToUserUrl' => '/users/' . $oldToUser->name,
                                            'oldFromUserId'=> $oldFromUser->id,
                                            'oldToUserId'=> $oldToUser->id,
                                            'oldFromUserIcon' => $oldFromUser->profile->icon,
                                            'oldToUserIcon'=> $oldToUser->profile->icon,
                                            'status'=> 'override'
                                        );
                            $ticket->from = $request->from;
                            $ticket->to = $toUser->id;
                            $ticket->used = '1';
                            $ticket->save();
                        } else {
                            $ticket->from = $request->from;
                            $ticket->to = $toUser->id;
                            $ticket->used = '1';
                            $ticket->save();
                            $info = array('fromUser' => $fromUser->nickname,
                                            'toUser'=> $toUser->nickname,
                                            'fromUserUrl' => '/users/' . $fromUser->name,
                                            'toUserUrl' => '/users/' . $toUser->name,
                                            'fromUserId' => $fromUser->id,
                                            'toUserId'=> $toUser->id,
                                            'fromUserIcon' => $fromUser->profile->icon,
                                            'toUserIcon'=> $toUser->icon,
                                            'status'=> 'new'
                                        );
                        }
                    }
                }
            }
        }
        return response()->json(['qutteInfo' => $info], 200);
    }

    public function confirmFetch(Request $request)
    {
        if ($request->qutteId != '') {
            $ticket = Ticket::where('id', $request->qutteId)->first();
            if (empty($ticket)) {
                $info = array('status'=> 'unknown');
            } elseif ($ticket->done == 1) {
                $info = array('status'=> 'done');
            } elseif (!isset($ticket->from)) {
                $info = array('status'=> 'none');
            } else {
                logger('aaa', ['tweets' => $ticket]);
                $fromUser = User::where('id', $ticket->from)->first();
                $toUser = User::where('id', $ticket->to)->first();
                $info = array('fromUser' => $fromUser->nickname,
                                'toUser'=> $toUser->nickname,
                                'fromUserUrl' => '/users/' . $fromUser->name,
                                'toUserUrl' => '/users/' . $toUser->name,
                                'fromUserId' => $fromUser->id,
                                'toUserId'=> $toUser->id,
                                'fromUserIcon' => $fromUser->profile->icon,
                                'toUserIcon'=> $toUser->profile->icon,
                                'status'=> 'confirm'
                            );
            }
        }

        return response()->json(['qutteInfo' => $info], 200);
    }
}
