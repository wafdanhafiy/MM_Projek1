<?php

namespace App\Http\Controllers;

use App\Http\HelperApi\HelperApi;
use App\Models\Activity;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $activity = Activity::where('user_id', $user['id'])
            ->orderBy('name', 'ASC')
            ->get();

        $response = [
            'message' => 'List Activity Ordered by Name',
            'data' => $activity,
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'desc' => ['required'],
            'start' => ['required'],
            'user_id' => ['required'],
            'category_id' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $activity = Activity::create($request->all());
            $response = [
                'message' => 'activity created',
                'data' => $activity,
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Failed ' . $e->errorInfo,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $activity = Activity::findOrFail($id);
        $response = [
            'message' => 'Detail Of Activity',
            'data' => $activity,
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'desc' => ['required'],
            'start' => ['required'],
            'user_id' => ['required'],
            'category_id' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $activity->update($request->all());
            $response = [
                'message' => 'Activity updated',
                'data' => $activity,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Failed ' . $e->errorInfo,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        try {
            $activity->delete();
            $response = [
                'message' => 'Activity deleted',
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Failed ' . $e->errorInfo,
            ]);
        }
    }

    public function stats(Request $request)
    {
        $user = $request->user();

        $object = [];

        for ($i = 6; $i >= 0; $i--) {
            $object[] =  HelperApi::createobject($user, $i);
        }

        //count all

        //work count
        $work = Activity::where('user_id', $user['id'])
            ->where('category_id', '1')
            ->orderBy('name', 'ASC')
            ->count();

        //learn count
        $learn = Activity::where('user_id', $user['id'])
            ->where('category_id', '2')
            ->orderBy('name', 'ASC')
            ->count();

        //play count
        $play = Activity::where('user_id', $user['id'])
            ->where('category_id', '3')
            ->orderBy('name', 'ASC')
            ->count();


        //response
        $response = [
            'total' => [
                'work' => $work,
                'learn' => $learn,
                'play' => $play,
            ],
            'timeframe' =>  $object,
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    public function getactivitybycategory($id)
    {
        $activity = Activity::where('category_id', $id)
            ->orderBy('start', 'ASC')
            ->get();

        $response = [
            'message' => 'List Activity Ordered by Name',
            'data' => $activity,
        ];

        return response()->json($response, Response::HTTP_OK);
    }
}
