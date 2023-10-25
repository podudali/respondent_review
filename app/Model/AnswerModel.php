<?php

namespace App\Model;

use Nette\Database\Explorer;

class AnswerModel
{
    private $database;
    public function __construct(Explorer $database)
    {
        $this->database = $database;
    }

    public function getQuestion(): array
    {
        $otazky = $this->database->table('answer');
        foreach ($otazky as $otazka) {
            $question = $otazka->question;
            $questions[] = $question;
        }
        return ['question' => $question];
    }

    public function getAnswer(): array
    {
        $odpovedy = $this->database->table('answer');
        foreach ($odpovedy as $odpoved) {
            $answer = $odpoved->answer;
            $answers[] = $answer;
        }
        return ['answer' => $answer];
    }
}
