<?php

namespace App\Http\Controllers;

use App\Favourite;
use App\Trick;
use App\TrickPlace;
use App\User;
use Illuminate\Http\Request;
use Validator;

class APIController extends Controller
{
    public function store_user(Request $request)
    {
        // Validate user input then store to database
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|max:255',
            'lastName' => 'required|max:255',
            'password' => 'required',
            'email' => 'required|email|unique:users,email|max:255',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'code' => 400,
                'message' => 'Invalid request',
                'errors' => $errors,
            ], 400);
        };

        $user = new User;
        $user->firstName = $request->firstName;
        $user->lastName = $request->lastName;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        // return a succesfull response
        return response()->json([
            'message' => 'User Created Succesfully',
        ], 200);

    }

    public function store_tricks(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tricks.*.name' => 'required|max:255',
            'tricks.*.description' => 'required|max:1000',
            'user_id' => 'required',
            'tricks.*.video' => 'mimetypes:video/avi,video/mpeg,video/quicktime',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'code' => 400,
                'message' => 'Invalid request',
                'errors' => $errors,
            ], 400);
        };
        if ($request->tricks < 1) {
            return response()->json([
                'code' => 400,
                'message' => 'Invalid request',
                'errors' => ' Add atleast one trick to proceeed',
            ], 400);
        }

        // Loop through all tricks uploaded and save them to db
        foreach ($request->tricks as $tr) {
            $trick = new Trick;
            $trick->name = array_get($tr, 'name');
            $trick->description = array_get($tr, 'description');
            $trick->user_id = $request->user_id;
            $trick->save();

            // Get the list of places and upload to db
            $places = explode(',', array_get($tr, 'places'));
            foreach ($places as $place) {
                $trick_place = new TrickPlace();
                $trick_place->trick_id = $trick->id;
                $trick_place->name = $place;
                $trick_place->save();
            }

            // Add video to trick if available
            if (array_get($tr, 'video')) {
                $video = array_get($tr, 'video');
                $timestamp = str_replace([' ', ':'], '-', date("Ymd"));
                $name = $timestamp . $video->getClientOriginalName();
                $video->move(public_path() . '/video/', $name);

                // Update trick if video is present
                $trick->video_url = '/video/' . $name;
                $trick->save();
            }

        }

        // return a succesfull response
        return response()->json([
            'message' => 'Trick created succesfully',
        ], 200);

    }

    public function tricks_index(Request $request)
    {
        if ($request->has('count')) {
            return response()->json(
                [
                    'message' => 'Tricks fetched succesfully',
                    'tricks' => Trick::with('places')
                        ->latest()
                        ->limit($request->count)
                        ->get(),
                ]
            );

        } else {
            return response()->json(
                [
                    'message' => 'Tricks fetched succesfully',
                    'tricks' => Trick::with('places')
                        ->latest()
                        ->get(),
                ]
            );

        }

    }

    public function tricks_favourite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'is_favourite' => 'required|boolean|max:255', //Check if favourite is true or false
            'user_id' => 'required',
            'trick_id' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'code' => 400,
                'message' => 'Invalid request',
                'errors' => $errors,
            ], 400);
        };

        // Update the db with what the user has stated, whether favourite or not

        // DB::table('user_tricks')
        //     ->where('trick_id', $request->trick_id)
        //     ->where('user_id', $request->user_id)
        //     ->update(['favourite' => $request->is_favourite]);

        $favourite = Favourite::firstOrNew([
            'trick_id' => $request->trick_id,
            'user_id' => $request->user_id,
        ]);
        $favourite->favourite = $request->is_favourite;
        $favourite->save();

        // return a response
        if ($request->is_favourite) {
            return response()->json([
                'message' => 'Trick favourited succesfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Trick unfavourited succesfully',
            ], 200);

        }
    }

}
