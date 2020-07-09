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
    protected $table = 'quiz_session_question';

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
    public function questions() {
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
        // question ready to be answered
        if ($this->time_limit > time() && empty($this->answer)) {
            return 'ACTIVE';
        }

        // question answered correctly
        if (isset($this->answer) && $this->answer->is_correct) {
            return 'ANSWER_CORRECT';
        }

        // question answered wrongly
        if (isset($this->answer) && !$this->answer->is_correct) {
            return 'ANSWER_WRONG';
        }

        // question hasn't started yet
        return 'INACTIVE';
    }

}
