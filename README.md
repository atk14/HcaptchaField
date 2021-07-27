HcaptchaField
==============

HcaptchaField is a field for protecting forms against spam in ATK14 applications.

It uses [hCaptcha API](https://www.hcaptcha.com/).

HcaptchaField is an alternative to [RecaptchaField](https://packagist.org/packages/atk14/recaptcha-field) without Google.

Installation
------------

Just use the Composer:

    cd path/to/your/atk14/project/
    composer require atk14/hcaptcha-field

You must define two constants in config/settings.php. Sign-up to hCaptcha and get their right values at https://dashboard.hcaptcha.com/sites?page=1 and https://dashboard.hcaptcha.com/settings

    <?php
    // file: config/settings.php

    // ...
    define("HCAPTCHA_SITE_KEY","178bdce1-a1be-5969-c712-852308e012a0");
    define("HCAPTCHA_SECRET_KEY","0x15722de15712c967eA265F86c79A51a46e461F00";

Optionally you can symlink the HcaptchaField files into your project:

    ln -s ../../vendor/atk14/hcaptcha-field/src/app/fields/hcaptcha_field.php app/fields/hcaptcha_field.php
    ln -s ../../vendor/atk14/hcaptcha-field/src/app/widgets/hcaptcha_widget.php app/widgets/hcaptcha_widget.php

Usage in a ATK14 application
----------------------------

In a form:

    <?php
    // file: app/forms/users/create_new_form.php
    class CreateNewForm extends ApplicationForm {

      function set_up(){
        $this->add_field("firstname", new CharField([
          "label" => "Firstname",
          "max_length" => 200,
        ]));

        $this->add_field("lastname", new CharField([
          "label" => "Lastname",
          "max_length" => 200,
        ]));

        // other fields

        $this->add_field("captcha",new HcaptchaField([
          "label" => "Spam protection"
        ]));
      }

      function clean(){
        list($err,$values) = parent::clean();

        // perhaps you may not want to have "captcha" in the cleaned data
        if(is_array($values)){ unset($values["captcha"]); }

        return [$err,$values];
      }
    }

In a template (a shared template from the [Atk14Skelet](https://github.com/atk14/Atk14Skelet) is used):

    <h1>User Registration</h1>

    {form}

      <fieldset>
        {render partial="shared/form_field" fields="firstname,lastname,..."}
      </fieldset>

      <fieldset>
        {render partial="shared/form_field" fields="captcha"}
      </fieldset>

      <button type="submit">Register</button>

    {/form}

In a controller:

    <?php
    // file: app/controllers/users_controller.php
    class UsersController extends ApplicationController {
      
      function create_new(){
        if($this->request->post() && ($values = $this->form->validate($this->params))){
          // There's no need to care about the $values["captcha"] since it was unset in CreateNewForm::clean()
          User::CreateNewRecord($values);
          $this->flash->success("Your registration has been successfully performed");
          $this->_redirect_to("main/index");
        }
      }
    }

License
-------

HcaptchaField is free software distributed [under the terms of the MIT license](http://www.opensource.org/licenses/mit-license)
