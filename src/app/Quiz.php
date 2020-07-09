<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'quizzes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
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
     * Quiz sessions relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quizSessions() {
        return $this->hasMany('App\QuizSession');
    }

    /**
     * The questions relationship
     */
    public function questions() {
        return $this->hasMany('App\Question')
            ->with('answers');
    }
}
