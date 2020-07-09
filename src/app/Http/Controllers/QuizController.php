<?php

namespace App\Http\Controllers;

use App\Http\Resources\Quiz as QuizResource;
use App\Http\Resources\QuizSessionQuestion as QuizSessionQuestionResource;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;


class QuizController extends Controller
{
    /**
     * Display a listing of all the quizes
     *
     * @return QuizCollection
     */
    public function index()
    {
        return QuizResource::collection(\App\Quiz::query()->paginate());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $token = null)
    {
        // get quiz
        $quiz = \App\Quiz::find($id);

        // get quizSession
        $quizSession = $quiz->quizSessions
            ->where('token', '=', $token)
            ->first();

        // create new quizSession
        if (is_null($quizSession)) {
            $quizSession = \App\QuizSession::create(['quiz_id' => $id]);
        }

        // paginate questions (one per page)
        $sessionQuestions = $quizSession->sessionquestions()->paginate(1);
        $sessionQuestions->withPath(
            action('QuizController@show', ['id' => $quiz->id, 'token' => $quizSession->token])
        );

        // set time limit for current question if needed
        $currentQuestion = $sessionQuestions->items()[0];
        if ($currentQuestion->state === 'INACTIVE') {
            $currentQuestion->time_limit = \Carbon\Carbon::now()->addSeconds(config('quiz.time_limit'))->toDateTimeString();
            $currentQuestion->save();
        }

        // return questions resource
        return QuizSessionQuestionResource::collection($sessionQuestions)
                ->additional([
                    'meta' => [
                        'quiz_id' => $quiz->id,
                        'quiz_name' => $quiz->name,
                    ]
                ]);
    }

    /**
     * Handle response to a session question
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submitAnswer(Request $request, $id)
    {
        // get session question and quiz question
        $sessionQuestion = \App\QuizSessionQuestion::find($id);
        $question = $sessionQuestion->question;

        // only change when state is active
        if ($sessionQuestion->state === 'ACTIVE') {
            // find answer
            $answer = \App\Answer::query()
                ->whereHas('question', function($query) use ($question) {
                    // only get answers from the correct question
                    $query->where('id', '=', $question->id);
                })
                ->find($request->input('answer_id'));

            // get answer from request
            if (!empty($answer)) {
                $sessionQuestion->answer()->associate($answer)->save();
            }
        }

        // return as resource
        return new QuizSessionQuestionResource($sessionQuestion);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function totalResult(Request $request, $id)
    {
        //
    }
}
