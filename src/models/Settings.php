<?php

namespace Isev\ContactFormValidation\models;

use craft\base\Model;

class Settings extends Model
{
    public $validate = [
        'fromName' => 'required',
        'fromEmail' => 'required|valid_email',
    ];
    public $filter = [
        'fromName' => 'trim',
        'fromEmail' => 'trim|sanitize_email',
    ];
    public $readableNames = [
        'fromEmail' => 'email',
        'fromName' => 'name',
    ];

    public function rules()
    {
        return [
            // [['validate', 'filter', 'friendlyNames'], '']
        ];
    }
}