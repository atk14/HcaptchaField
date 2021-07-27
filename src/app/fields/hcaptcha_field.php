<?php
class HcaptchaField extends CharField {

	function __construct($options = array()){
		$options = array_merge(array(
			"widget" => new HcaptchaWidget(),
			"error_messages" => array(
				"required" => _("Please try to solve the test. It is important for us to be sure that we are communicating with a human."),
				"invalid" => _("The test was not successful. Please try it again."),
				"service_unavailable" => _("We are experiencing some technical issue during the communication with Google. Please try it later."),
			)
		),$options);
		parent::__construct($options);

		if(!defined("HCAPTCHA_SITE_KEY") || !defined("HCAPTCHA_SECRET_KEY")){
			throw new Exception("To use HcaptchaField you must define HCAPTCHA_SITE_KEY and HCAPTCHA_SECRET_KEY as it is mentioned on https://github.com/atk14/HcaptchaField");
		}
	}

	function clean($value){
		list($error,$value) = parent::clean($value);
		if($error || !$value){
			return array($error,$value);
		}

		$request = &$GLOBALS["HTTP_REQUEST"];

		$response = trim($request->getPostVar("g-recaptcha-response"));
		if(strlen($response)==0){
			return array($this->messages["required"],null);
		}

		$uf = new UrlFetcher(sprintf("https://hcaptcha.com/siteverify?secret=%s&response=%s",urlencode(HCAPTCHA_SECRET_KEY),urlencode($response)));
		if(!$uf->found()){
			return array($this->messages["service_unavailable"],null);
		}

		$data = json_decode($uf->getContent(),true); // { "success": true }
		if(!$data){
			return array($this->messages["service_unavailable"],null);
		}

		if($data["success"]!==true){
			return array($this->messages["invalid"],null);
		}
		return array(null,"ok");
	}
}
