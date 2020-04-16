<?php

namespace App\Http\Controllers;

use App\ForumReply;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\ForumThread;
use App\Inspections\Spam;
use App\Notifications\YouWereMentioned;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class ForumReplyController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($channelId, ForumThread $thread)
    {

        return $thread->replies()->paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store($channelId, ForumThread $thread,Request $request)
    {


        try {

            $this->validateRequest();

            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()
            ]);

            //increment user experience
            $reply->owner->updateExperience(30);


        } catch (\Exception $e) {
            \Log::info($e);
            return response('Sorry, your reply could not be saved at this time.',422);

        }

        //if the request is ajax call
        if( request()->expectsJson()){

            return $reply->load('owner');
        }

        return back()->with('flash-message','Your reply has been posted!');
    }

    /**
     * Display the specified resource.
     *
     * @param ForumReply $forumReply
     * @return Response
     */
    public function show(ForumReply $forumReply)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ForumReply $forumReply
     * @return Response
     */
    public function edit(ForumReply $forumReply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param ForumReply $forumReply
     * @return Response
     */
    public function update(Request $request, $forumReply)
    {

        try {

            $this->validateRequest();

            $forumReply = ForumReply::find($forumReply);

            $this->authorize('update', $forumReply);

            $forumReply->update([
                'body' => request('body')
            ]);

        } catch (\Exception $e) {
            return response('Sorry, your reply could not be saved at this time.',422);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ForumReply $forumReply
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($forumReply)
    {

        $forumReply =  ForumReply::find($forumReply);

        $this->authorize('update', $forumReply); //will be verified through policy

        $forumReply->delete();

        // if the request type is ajax then just pass the response and not redirect back
        if(request()->expectsJson()){

            return response(['status' => 'Reply deleted']);
        }

        return back();


    }

    protected function validateRequest(){

        $this->validate(request(), [
            'body' => 'required'
        ]);

        resolve(Spam::class)->detect(request('body'));// with resolve you dont have to inject into class constructor

    }
}
