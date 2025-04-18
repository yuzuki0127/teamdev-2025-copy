<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SurveyRequestMail extends Mailable
{
    use SerializesModels;

    public $user;
    public $employee;
    public $countSurvey;
    public $survey;
    public $result;

    public function __construct($user, $employee, $countSurvey, $survey, $result)
    {
        $this->user = $user;
        $this->employee = $employee;
        $this->countSurvey = $countSurvey;
        $this->survey = $survey;
        $this->result = $result;
    }

    public function build()
    {
        return $this->from($this->user->email, $this->user->name)
                    ->subject("{$this->user->team->team_name}：第{$this->countSurvey}回 組織改善アンケート")
                    ->view('emails.surveyRequest')
                    ->with([
                        'user' => $this->user,
                        'employee' => $this->employee,
                        'survey' => $this->survey,
                        'result' => $this->result,
                    ]);
    }
}
