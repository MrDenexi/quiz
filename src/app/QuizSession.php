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
        'id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'quiz_id' => 'integer'
    ];

    /**
     * Overwrite create to add quizsession questions
     *
     * @param array $attributes
     * @return QuizSession
     */
    public static function create(array $attributes = []){
        // add token to attributes
        $attributes['token'] = hash('sha256', \Str::random(60));

        // create model normally
        $model = static::query()->create($attributes);

        // add session question
        $model = $model->addSessionQuestions();

        // return model
        return $model;
    }

    /**
     * Method to add session questions using quiz questions
     *
     * @return void
     */
    public function addSessionQuestions() {
        // return if quiz is not complete yet
        if (empty($this->quiz) && empty($this->quiz->questions)) {
            return $this;
        }

        // get question and shuffle them
        $quizQuestions = $this->quiz->questions
            ->shuffle();

        // create session questions
        $quizQuestions
            ->map(function($quizQuestion, $index) {
                // create new session question
                $sessionQuestion = new \App\QuizSessionQuestion();
                $sessionQuestion->order = $index;

                // add relationships
                $sessionQuestion->question()->associate($quizQuestion);
                $sessionQuestion->quizSession()->associate($this);

                // save and return
                $sessionQuestion->save();
                return $sessionQuestion;
            });

        // load relationships
        $this->load(['sessionQuestions']);

        // return
        return $this;
    }

    public function quiz(){
        return $this->belongsTo('App\Quiz', 'quiz_id', 'id');
    }

    public function sessionQuestions() {
        return $this->hasMany('App\QuizSessionQuestion')
            ->orderBy('order');
    }
}
