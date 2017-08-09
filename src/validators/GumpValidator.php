<?php namespace Isev\ContactFormValidation\validators;

use \GUMP;
class GumpValidator {

    protected $validator;

    protected $settings;

    public function __construct($validator, $settings) {
        $this->validator = $validator;
        $this->settings = $settings;
    }

    public function check($event) {

        $readableNames = $this->settings['readableNames'];

        foreach($readableNames as $key => $value) {
            $this->validator->set_field_name($key, $value);
        };

        $submission = $event->sender;

        $form_input = $this->buildForm($submission);

        $this->validator->validation_rules($this->settings->validate);
        $this->validator->filter_rules($this->settings->filter);
        $validated_data = $this->validator->run($form_input);

        if($validated_data === false) {
            foreach($this->validator->get_errors_array() as $key => $value) {
                $submission->addError($this->getFieldName($key), $value);
            }
            $event->isValid = false;
        } else {
            if(is_array($submission->message)) {
                foreach($submission->message as $key => $value) {
                    $submission->message[$key] = $validated_data[$key];
                }
            } else {
                $submission->message = $validated_data['message'];
            }
            $submission->fromEmail = $validated_data['fromEmail'];
            $submission->fromName = $validated_data['fromName'];
            $submission->subject = $validated_data['subject'];
            if($validated_data['fromEmail']) {
                $submission->message['From Email'] = $validated_data['fromEmail'];
            }
            if($validated_data['fromName']) {
                $submission->message['From Name'] = $validated_data['fromName'];
            }
            if($validated_data['subject']) {
                $submission->message['Subject'] = $validated_data['subject'];
            }
        }
        return $event;
    }

    protected function buildForm($submission) {
        /*
         * If message is an array then it's split into multiple form inputs
         * loop through them and add them to the form input variable
         * Other wise it's a string, meaning it's a standard form with no added extra inputs
         */
        if(is_array($submission->message)) {
            $form_input = [];
            foreach($submission->message as $key => $value) {
                $form_input[$key] = $value;
            }
        } else {
            $form_input = [
                'message' => $e->sender->message,
            ];
        }

        // Add the rest of the default forms input options
        $form_input['fromEmail'] = $submission->fromEmail;
        $form_input['fromName'] = $submission->fromName;
        $form_input['subject'] = $submission->subject;
        return $form_input;
    }

    protected function getSettings() {
        return $this->settings;
    }

    protected function getFieldName($key) {
        if(in_array($key, ['fromEmail', 'fromName', 'subject', 'message'])) {
            return $key;
        }
        return 'message.'.$key;
    }

}