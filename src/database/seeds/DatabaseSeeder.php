<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // create quiz
        $quiz = \App\Quiz::create(['name' => 'Techniek en wetenschap!']);

        // questions with answers
        // first answer is correct
        $questionsAndAnswers = collect([
            "In welke maateenheid wordt een beeldscherm aangeduid?" => ["Inch", "Centimeter", "Foot", "Duim"],
            "Welke toetsenbord indeling gebruiken we in Nederland?"	=> ["Qwerty", "Azerty", "Dvorak", "Cyrilisch"],
            "Hoe noemt men een kunststof colafles?" => ["Petfles", "Hoedfles", "Sokfles", "Broekfles"],
            "Wat is een andere benaming voor sifon?" => ["Zwanenhals", "Gansenbek", "Hondentong", "Vouwfiets"],
            "Hoeveel potloodbatterijen maken samen 4,5 volt?" => ["3", "Een potlood is geen batterij", "1", "39"],
            "Wat is de tegenhanger van gelijkspanning?" => ["Wisselspanning", "Negatiefspanning", "Driefasen", "Ongelijkspanning"],
            "Hoe noem je de lamp die als koplamp in de auto wordt gebruikt?" => ["Halogeen", "Zon", "Gloeilamp", "Zaklamp"],
            "Wat geeft een gloeilamp het meest af?" => ["Warmte", "Licht", "Geur", "Vlekken"],
            "In welke stad staat de Technische Universiteit (TU)?" => ["Delft", "Goes", "Aalsmeer", "Groningen"],
            "Hoe luidt de stelling van Pythagoras?" => ["a2+b2=c2", "e=mc^2", "U=I*R", "y=a*x+b"]
        ]);

        // loop over questions
        foreach ($questionsAndAnswers as $q => $answers) {
            // create question model
            $question = \App\Question::create(['quiz_id' => $quiz->id, 'content' => $q]);
            // add answers to question
            foreach ($answers as $key => $value) {
                $answer = new \App\Answer(['content' => $value]);
                $key === 0 ? $answer->is_correct = true : $answer->is_correct = false;
                $question->answers()->save($answer);
            }
            // add question to quiz
            $quiz->questions()->save($question);
        }
    }
}
