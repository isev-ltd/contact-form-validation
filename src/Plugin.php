<?php
namespace Isev\ContactFormValidation;

use craft\contactform\models\Submission;
use yii\base\Event;
use yii\base\ModelEvent;
use Isev\ContactFormValidation\validators\GumpValidator;

class Plugin extends \craft\base\Plugin
{
    protected $validator = null;

    public function init()
    {
        parent::init();

        // Custom initialization code goes here...
        Event::on(Submission::class, Submission::EVENT_BEFORE_VALIDATE, function(ModelEvent $event) {

            $event = $this->getValidator()->check($event);

        });
    }
    protected function createSettingsModel()
    {
        return new \Isev\ContactFormValidation\models\Settings();
    }

    protected function getValidator() {
        if($this->validator !== null) {
            return $this->validator;
        }
        return (new GumpValidator((new \GUMP), $this->getSettings()));
    }
}