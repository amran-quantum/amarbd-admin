<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Mail;
use App\Game;
use App\Question;
use App\Notification;

class GameController extends Controller
{
    public function showGame(Request $request)
    {
        $games = Game::with('total_questions')->paginate(10);

        return response()->json([
            'games' => $games,
            'success' => true
        ], 200);
    }
    public function showGameApi(Request $request)
    {
        $games = Game::all();
        return response()->json([
            'games' => $games,
            'success' => true
        ], 200);
    }
    public function addGame(Request $request)
    {
        $data = $request->all();
        $count = Game::where('title', $data['title'])->first();
        if ($count) {
            return response()->json([
                'msg' => 'This title is already used!',
                'success' => false
            ], 422);
        }
        $data['created_at'] = $data['updated_at'] = date('Y-m-d');
        $games = Game::create($data);
        return response()->json([
            'games' => $games,
            'success' => true
        ], 200);
    }
    public function editGame(Request $request)
    {
        $data = $request->all();
        $count = Game::where('title', $data['title'])->where('id', '!=', $data['id'])->first();
        if ($count) {
            return response()->json([
                'msg' => 'This title is already used!',
                'success' => false
            ], 422);
        }
        $g = Game::where('id', $data['id'])->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'age_range' => $data['age_range'],
            'cover_photo' => $data['cover_photo'],
            'updated_at' => date('Y-m-d')
        ]);
        if ($g) {
            $games = Game::where('id', $data['id'])->first();
        }
        return response()->json([
            'games' => $games,
            'success' => true
        ], 200);
    }
    public function deleteGame(Request $request)
    {
        $data = $request->all();
        $games = Game::where('id', $data['id'])->delete();

        return response()->json([
            'games' => $games,
            'success' => true
        ], 200);
    }
    public function addQuestion(Request $request)
    {
        $data = $request->all();

        $questions = Question::create([
            'game_id' => $data['game_id'],
            'question' => $data['question'],
            'options' => $data['options'],
            'right_answer' => $data['right_answer']
        ]);
        return response()->json([
            'questions' => $questions,
            'success' => true
        ], 200);
    }
    public function editQuestion(Request $request)
    {
        $data = $request->all();

        $g = Question::where('id', $data['id'])->update([
            'game_id' => $data['game_id'],
            'question' => $data['question'],
            'options' => $data['options'],
            'right_answer' => $data['right_answer'],
            'updated_at' => date('Y-m-d')
        ]);
        if ($g) {
            $questions = Question::with('game')->where('id', $data['id'])->first();
        }
        return response()->json([
            'questions' => $questions,
            'success' => true
        ], 200);
    }
    public function deleteQuestion(Request $request)
    {
        $data = $request->all();
        $questions = Question::where('id', $data['id'])->delete();

        return response()->json([
            'questions' => $questions,
            'success' => true
        ], 200);
    }
    public function showQuestion(Request $request)
    {
        $questions = Question::with('game')->orderBy('game_id', 'desc')->paginate(10);

        return response()->json([
            'questions' => $questions,
            'success' => true
        ], 200);
    }
    public function addNotification(Request $request)
    {
        $data2 = $request->all();
        $data2['created_at'] = $data2['updated_at'] = date('Y-m-d');
        $notifications = Notification::create($data2);
        $mob = User::where('id', $data2['user_id'])->first();

        if ($mob->new_courses == 0 && $data2['type'] == 'new courses') {
            return response()->json([
                'msg' => $mob->name . ' did not permitted for new courses',
                'success' => false
            ], 401);
        }
        if ($mob->email_notifications == 0 && $data2['type'] == 'email') {
            return response()->json([
                'msg' => $mob->name . ' did not permitted for email notifications',
                'success' => false
            ], 401);
        }
        if ($mob->push_notifications == 0 && $data2['type'] == 'push') {
            return response()->json([
                'msg' => $mob->name . ' did not permitted for push notifications',
                'success' => false
            ], 401);
        } else {


            if ($mob->new_courses == 1 && $data2['type'] == 'new courses') {
                $ids = [];
                $ids[0] = $mob->mobile_token;
                //firebase
                $url = 'https://fcm.googleapis.com/fcm/send';

                $fields = array(
                    'registration_ids' => $ids,
                    'data' => array(
                        'title' => 'Edujust',
                        "message" => $data2['msg'],
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'n_id' => $notifications->id,

                    ),
                    'notification' => array(
                        'title' => 'Edujust',
                        "body" => $data2['msg'],
                        "sound" => true,
                        "badge" => 1,
                    ),
                    'time_to_live' => 6000,
                );
                $fields = json_encode($fields);
                $headers = array(
                    'Authorization: key=' . "AAAA6bqa7i8:APA91bE4BN_ffTTSbizCO6vpHs_n3hpOXKPrbsFHCEYqHuSQ9G8qwpcGkEHPB1-8bjPZTSIpqoQqvKBP5zmKUk2ah_9muxED0-0rxhJ2resos-sc7CmpXAXn9shH0_bxC_gP-fLD7oD_",
                    'Content-Type: application/json'
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                $result = curl_exec($ch);
                curl_close($ch);
            }
            if ($mob->email_notifications == 1 && $data2['type'] == 'email') {
                $email = $mob->email;
                $data['bodyMessage'] = $data2['msg'];
                \Mail::send('testmail', $data, function ($message) use ($email) {
                    $message->from('dazzling.cloudlet@gmail.com', 'Just Laravel');
                    $message->to($email)->subject('Just Laravel demo email using SendGrid');
                });
            }

            if ($mob->push_notifications == 1 && $data2['type'] == 'push') {
                $ids = [];
                $ids[0] = $mob->mobile_token;
                //firebase
                $url = 'https://fcm.googleapis.com/fcm/send';

                $fields = array(
                    'registration_ids' => $ids,
                    'data' => array(
                        'title' => 'Edujust',
                        "message" => $data2['msg'],
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'n_id' => $notifications->id,

                    ),
                    'notification' => array(
                        'title' => 'Edujust',
                        "body" => $data2['msg'],
                        "sound" => true,
                        "badge" => 1,
                    ),
                    'time_to_live' => 6000,
                );
                $fields = json_encode($fields);
                $headers = array(
                    'Authorization: key=' . "AAAA6bqa7i8:APA91bE4BN_ffTTSbizCO6vpHs_n3hpOXKPrbsFHCEYqHuSQ9G8qwpcGkEHPB1-8bjPZTSIpqoQqvKBP5zmKUk2ah_9muxED0-0rxhJ2resos-sc7CmpXAXn9shH0_bxC_gP-fLD7oD_",
                    'Content-Type: application/json'
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                $result = curl_exec($ch);
                curl_close($ch);
            }

            //end-firebase
        }



        $notifications = Notification::create($data2);

        return response()->json([
            'notifications' => $notifications,
            'success' => true
        ], 200);
    }

    public function deleteNotification(Request $request)
    {
        $data = $request->all();
        $notifications = Notification::where('id', $data['id'])->delete();

        return response()->json([
            'notifications' => $notifications,
            'success' => true
        ], 200);
    }
    public function showNotification(Request $request)
    {
        $notifications = Notification::with('user')->orderBy('id', 'desc')->paginate(10);

        return response()->json([
            'notifications' => $notifications,
            'success' => true
        ], 200);
    }
}
