<?php

namespace App\Presenters;

use Nette\Application\UI\Form;
use Nette\Database\Explorer;
use Nette\Http\Request;
use Nette\Utils\DateTime;

class CheckPresenter extends \Nette\Application\UI\Presenter

{
    private $database;
    private $form;
    private $request;

    public function __construct(Explorer $database, Form $form, Request $request)
    {
        $this->database = $database;
        $this->form = $form;
        $this->request = $request;
    }

    protected $respondentId;

    protected function createComponentVerificationForm(): Form
    {
        $form = new Form;
        $form->addSubmit('send', 'Calculate the grades')
            ->setAttribute('id', 'send-button');

        $form->addHidden('respondentId')->setDefaultValue($this->getParameter('respondentId'));
        $form->onSuccess[] = function (Form $form, \stdClass $values) {
            $this->respondentId = $values->respondentId; // Сохраняем значение в свойстве класса
            $this->checkResults($form, $values);
        };
        return $form;
    }
    
    public function checkResults(Form $form, \stdClass $values): void
    {
        $id = $this->respondentId; // Получаем значение из свойства класса
        $points = $this->database->table('answer')->select('rating')->where('id', $id)->fetchAll();
        $sum = 0;
        $count = count($points)-1;
        foreach ($points as $point) {
            $sum += intval($point->rating);
        }
        $average = ($count > 0) ? $sum / $count : 0;
        echo $average;
        $this->database->table('persons')->where('id', $id)->insert([
            'points' => $average,
            'date' => new DateTime,
        ]);
    }
}
