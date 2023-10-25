<?php

namespace App\Model;

use Nette\Database\Explorer;

class GptModel
{
    private $database;
    public function __construct(Explorer $database)
    {
        $this->database = $database;
    }

    public function checkAnswer(string $question, string $answer): string
    {
        $keys = $this->database->table('gpt');
        foreach ($keys as $key) {
            $token = $key->gpt;
        }
        $key = $token;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/chat/completions");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(
            "model" => "gpt-3.5-turbo",
            // "model" => "davinci",
            "messages" => array(
                // array("role" => "user", "content" => "Строго оцени ответ: '$answer' На вопрос: '$question' После этого дай только оценку ответу в виде балла(например: 80 из 100), больше ничего не пиши. Оценивай профессионально."),
                array("role" => "user", "content" => "Строго оцени ответ: '$answer' на вопрос о PHP: '$question'. Пожалуйста, учти следующие критерии при оценке:
                1. Точность и полнота ответа: насколько ответ полно и точно отражает сущность вопроса: '$question' о PHP.
                2. Профессионализм: насколько ответ соответствует принципам и правилам PHP-разработки.
                3. Четкость и структурированность изложения: насколько ответ логически структурирован и понятен.
                4. Актуальность: насколько ответ относится к последним версиям PHP и современным практикам.
                Оценивай ответ в виде баллов от 0 до 100, только баллов, ничего другого не пиши. Пожалуйста, будь внимательным и профессиональным при оценке. Благодарю!")
            ),
            "temperature" => 0.7,
        )));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Bearer $key",
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($response, true);
        $rating = $result["choices"][0]["message"]["content"];
        // Сохранение оценки в базу данных
        return $this->database->table('answer')->where('question', $question)->where('answer', $answer)->update(['rating' => $rating]);
    }
}
