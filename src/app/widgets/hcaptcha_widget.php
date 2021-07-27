<?php
class HcaptchaWidget extends Widget{
	function render($name, $value, $options = array()){
		global $ATK14_GLOBAL;
		$lang = $ATK14_GLOBAL->getLang(); // TODO: https://developers.google.com/recaptcha/docs/language
		$out = array();
		$out[] = '<script src="https://hcaptcha.com/1/api.js?hl='.$lang.'"></script>'; // TODO: Paste this snippet before the closing </head> tag on your HTML template:
		$out[] = '<div class="g-recaptcha" data-sitekey="'.HCAPTCHA_SITE_KEY.'"></div>';

		// This is a fake hidden field which makes the Form framework in thinking that the value is submitted as usually.
		// It is important for the Field::clean() method.
		$out[] = sprintf('<input type="hidden" name="%s" value="sent">',$name);

		return join("\n",$out);
	}
}
