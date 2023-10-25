<?php

namespace App\Model;

use Nette;
use Nette\Database\Explorer;

class NormalModel
{
    private  $database;
    public function __construct(Explorer $database){
        $this->database = $database;
    }

    public function getId(): int
    {
        $otazky = $this->database->table('normal_questions');
        foreach ($otazky as $otazka) {
            $id = $otazka->id;
        }
        return $id;
    }

    public function getQuestion(): array
    {
        $otazky = $this->database->table('normal_questions');
        foreach ($otazky as $otazka) {
            $questions = $otazka->question;
            $question[] = $questions;
        }
        // return $question;
        return ['question' => $question];
    }
}