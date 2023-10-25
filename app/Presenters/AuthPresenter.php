<?php

namespace App\Presenters;

use App\Model\AuthModel;
use App\Model\AnswerModel;
use Nette\Utils\DateTime;
use Nette\Database\Explorer;
use Nette\Application\UI\Form;

class AuthPresenter extends \Nette\Application\UI\Presenter
{
    private $model;
    private $database;
    private $answer;

    public function __construct(AuthModel $model, Explorer $database, AnswerModel $answer){
        $this->model = $model;
        $this->database = $database;
        $this->answer = $answer;
    }

    protected function createComponentLoginForm()
    {
        $form = new Form();

        $questions = [
            'Easy' => 'Easy',
            'Normal' => 'Average difficulty',
            'Hard' => 'You will not survive!',
        ];

        $username = $form->addText('username', 'Username:')
            ->setRequired('Please enter your username.');

        $surname = $form->addText('surname', 'Surname:')
            ->setRequired('Please enter your surname.');

        $form->addSelect('questions', 'Level:', $questions)
            ->setRequired('Choose your destiny.')
            ->setDefaultValue('Easy');

        $submit = $form->addSubmit('login', 'Start')
            ->setAttribute('id', 'send-button');

        $form->onSuccess[] = [$this, 'loginFormSucceeded'];

        return $form;
    }

    public function loginFormSucceeded(Form $form, $values)
    {
        $values = $form->getValues();
        $this->database->table('persons')->insert([
            'name' => $values['username'],
            'surname' => $values['surname'],
            'date' => new DateTime,
        ]);

        $this->database->table('answer')->insert([
            'id' => $this->database->table('persons')->select('id')->where('name', $values['username'])->where('surname', $values['surname'])->fetch()->id,
            'name' => $values['username'],
            'surname' => $values['surname']
        ]);

        $respondentId = $this->database->table('persons')->select('id')->where('name', $values['username'])->where('surname', $values['surname'])->fetch()->id;
        $respondentName = $this->database->table('persons')->select('name')->where('surname', $values['surname'])->fetch()->name;
        $respondentSurname = $this->database->table('persons')->select('surname')->where('name', $values['username'])->fetch()->surname;

        $easy_level = $this->database->table('easy_questions')->select('level')->fetch()->level;
        $normal_level = $this->database->table('normal_questions')->select('level')->fetch()->level;
        $hard_level = $this->database->table('hard_questions')->select('level')->fetch()->level; 

        $level = $values['questions'];

        if ($level === 'Easy') {
            $this->redirect('Easy:default', $respondentId, $respondentName, $respondentSurname, $easy_level);
        } elseif ($level === 'Normal') {
            $this->redirect('Normal:default', $respondentId, $respondentName, $respondentSurname, $normal_level);
        } elseif ($level === 'Hard') {
            $this->redirect('Hard:default', $respondentId, $respondentName, $respondentSurname, $hard_level);
        }

        $this->template->loginForm = $this->getComponent('loginForm');
        // $this->template->select = $this['loginForm']['selectField']->getControl();
        // $this->redirect('Otazky:default'); 
    }

}