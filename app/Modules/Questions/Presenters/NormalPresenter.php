<?php

namespace App\Presenters;

use Nette\Http\Request;
use App\Model\AnswerModel;
use App\Model\NormalModel;
use App\Model\GptModel;
use Nette\Application\UI\Form;
use Nette\Database\Explorer;
use Nette\Utils\DateTime;

class NormalPresenter extends \Nette\Application\UI\Presenter
{
    private $model;
    private $request;
    private $database;
    private $answer;
    private $gpt_check;

    public function __construct(NormalModel $model, Request $request, Explorer $database, AnswerModel $answer, GptModel $gpt_check)
    {
        parent::__construct();
        $this->model = $model;
        $this->request = $request;
        $this->database = $database;
        $this->answer = $answer;
        $this->gpt_check = $gpt_check;
    }

    public function actionDefault(string $respondentId, string $respondentName, string $respondentSurname, string $level, int $count = 0)
    {
        $this->template->form = $this->getComponent('form');
        $param = $this->request->getQuery('respondentId');
        $name = $this->request->getQuery('respondentName');
        $surname = $this->request->getQuery('respondentSurname');
        $level = $this->request->getQuery('level');

        $this->template->count = $count;
    }

    protected function createComponentForm()
    {
        $form = new Form();

        $questions = $this->model->getQuestion()['question'];

        $randomQuestion = $questions[array_rand($questions)];

        $form->addText('questions', 'Question:')
            ->setDefaultValue($randomQuestion)
            ->setAttribute('readonly', true);

        $answer = $form->addText('answer', 'Примечание:');

        $form->addHidden('question', $randomQuestion);

        $form->addSubmit('send', 'Send the answer')
            ->setAttribute('id', 'send-button');

        $form->onSuccess[] = [$this, 'sendAnswer'];

        $this->addComponent($form, 'form');

        return $form;
    }

    public function sendAnswer(Form $form, $values): void
    {
        $count = $this->getParameter('count', 0);
        $param = $this->getParameter('respondentId');
        $name = $this->getParameter('respondentName');
        $surname = $this->getParameter('respondentSurname');
        $level = $this->getParameter('level');
        if (!empty($values['answer'])) {
            $this->database->table('answer')->insert([
                'id' => $param,
                'name' => $name,
                'surname' => $surname,
                'level' => $level,
                'answer' => $values['answer'],
                'question' => $values['question'],
                'date' => new DateTime,
            ]);
            $this->gpt_check->checkAnswer($values['question'], $values['answer']);
        }
        $count++;
        $this->redirect('this', [
            'count' => $count,
        ]);
    }
}