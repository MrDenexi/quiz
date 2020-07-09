<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizSession extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'quiz_sessions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quiz_id', 'token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //
    ];

    public function quiz(){
        return $this->belongsTo('App\Quiz', 'quiz_id', 'id');
    }

    public function sessionQuestions() {
        return $this->belongsToMany('App\Question')
            ->using('App/QuestionQuizSession')
            ->withPivot(['time_limit', 'order'])
            ->orderBy('order');
    }
}
