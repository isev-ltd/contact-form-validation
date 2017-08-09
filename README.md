# Contact Form Validation

Simple server-side contact form validation for the [Craft CMS 3 Contact Form plugin](https://github.com/craftcms/contact-form) using [GUMP](https://github.com/Wixel/GUMP).

## Installation

Add the package to your ```composer.json``` file

```
"isev-ltd/contact-form-validation": "~0.0.1"
```

or install via command line 

```
composer require isev-ltd/contact-form-validation:0.0.*
```

Go to your Craft CMS 3 admin area **Settings**. Under **System** click **Plugins**.

Install ```Contact Form``` if you haven't already. Then install ```Contact Form Validation```.

Go back to **Settings**. Under **Plugins** go to **Contact Form** and make sure that your **to** email is added.

## Usage

Create a config file named ```config/contact-form-validation.php```

Add validate, filter, and readableNames keys to the returned array. Below is an example config file.

```php
<?php

return [
    'validate' => [
        'fromName' => 'required',
        'fromEmail' => 'required|valid_email',
        'phoneNumber' => 'required',
        'body' => 'required',
    ],
    'filter' => [
        'fromName' => 'trim',
        'fromEmail' => 'trim|sanitize_email',
        'phoneNumber' => 'trim',
        'body' => 'trim',
    ],
    'readableNames' => [
        'phoneNumber' => 'phone number',
        'fromEmail' => 'email',
        'fromName' => 'name',
        'body' => 'enquiry',
    ]
];
```


Note that, ```fromName```, ```fromEmail```, and ```subject``` are the default fields. Any other field will be within the ```message``` array, as with the Contact Form plugin.

## Example Form

The below form omits the default subject field, and adds a phone number. Because the phone number is custom, the message field is changed to ```message[body]```. Using the example config file all fields are required, and email needs to be an email address.

```twig
    {% macro errorList(errors) %}
        {% if errors %}
            <ul class="errors">
                {% for error in errors %}
                    <li>{{ error }}</li>
                {% endfor %}
            </ul>
        {% endif %}
    {% endmacro %}
    {% from _self import errorList %}

    <form method="post" action="" accept-charset="UTF-8">
        {{ csrfInput() }}
        <input type="hidden" name="redirect" value="{{ "#{craft.request.url}?sent=1"|hash }}">
        <input type="hidden" name="action" value="contact-form/send">

        <div class="contact-form--layout">
            <div class="contact-form--row">
                <div class="contact-form--label">
                    <label for="from-name" class="">Your Name:</label>
                </div>
                <div class="contact-form--input">
                    <input id="from-name" type="text" class="" name="fromName" value="{{ message.fromName ?? '' }}">
                    {{ message is defined and message ? errorList(message.getErrors('fromName')) }}
                </div>
            </div>

            <div class="contact-form--row">
                <div class="contact-form--label">
                    <label for="phoneNumber" class="">Phone Number:</label>
                </div>
                <div class="contact-form--input">
                    <input id="phoneNumber" type="text" class="" name="message[phoneNumber]" value="{{ message.message.phoneNumber ?? '' }}">
                    {{ message is defined and message ? errorList(message.getErrors('message.phoneNumber')) }}
                </div>
            </div>

            <div class="contact-form--row">
                <div class="contact-form--label">
                    <label for="from-email" class="">Your Email:</label>
                </div>
                <div class="contact-form--input">
                    <input id="from-email" type="email" class="" name="fromEmail" value="{{ message.fromEmail ?? '' }}">
                    {{ message is defined and message ? errorList(message.getErrors('fromEmail')) }}
                </div>
            </div>


            <div class="contact-form--row">
                <div class="contact-form--label">
                    <label for="message" class="">Your Enquiry:</label>
                </div>
                <div class="contact-form--input">
                    <textarea rows="10" cols="40" id="message" class="" name="message[body]">{{ message.message.body ?? '' }}</textarea>
                    {{ message is defined and message ? errorList(message.getErrors('message.body')) }}
                </div>
            </div>

            <div class="">
                <div class="contact-form--label">
                    <br>
                </div>
                <div class="contact-form--input">
                    <button type="submit" class="contact-form--submit">Submit</button>
                </div>
            </div>
        </div>
    </form>
```

## Validation rules

Currently this plugin only supports [GUMP](https://github.com/Wixel/GUMP) for validation, see the associated Github documentation for what's possible.

## Todo

* Allow using Illuminate Validator
* Testing
* Check Attachments
