<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Answer as AnswerResource;

class QuizSessionQuestion extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'id' => $this->id,
                'content' => $this->question->content,
                'state' => $this->state,
                'time_limit' => $this->time_limit,
                'answer_id' => $this->answer_id,
                'answers' => AnswerResource::collection($this->question->shuffledAnswers())
            ],
            'links' => [
                'self' => action('QuizController@submitAnswer', ['id' => $this->id]),
            ]

        ];
    }

}
