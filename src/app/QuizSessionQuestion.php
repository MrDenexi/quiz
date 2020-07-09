<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizSessionQuestion extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'quiz_session_questions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'answer_id'
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

    /**
     * The questions relationship
     */
    public function question() {
        return $this->belongsTo('App\Question')
            ->with('answers');
    }

    /**
     * The quiz session relationship
     */
    public function quizSession() {
        return $this->belongsTo('App\QuizSession');
    }

    /**
     * The chosen answer
     */
    public function answer() {
        return $this->belongsTo('App\Answer');
    }

    /**
     * Create a custom state attribute
     *
     * @return string
     */
    public function getStateAttribute() {
        // inactive if not time limit is set yet
        if (empty($this->time_limit)) {
            return 'INACTIVE';
        }

        // question answered correctly
        if (isset($this->answer) && $this->answer->is_correct) {
            return 'ANSWER_CORRECT';
        }

        // question answered correctly
        if (isset($this->answer) && !$this->answer->is_correct) {
            return 'ANSWER_WRONG';
        }

        // question ready to be answered
        if (\Carbon\Carbon::parse($this->time_limit) > \Carbon\Carbon::now()) {
            return 'ACTIVE';
        }

        // question is empty and not active
        return 'ANSWER_WRONG';
    }

}
