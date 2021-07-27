<?php
class TcHcaptchaField extends TcBase {

	function test(){
		global $HTTP_REQUEST;
		$HTTP_REQUEST = new HTTPRequest();

		$this->field = new HcaptchaField(array());

		$err = $this->assertInvalid("");
		$this->assertEquals("Please try to solve the test. It is important for us to be sure that we are communicating with a human.",$err);

		$HTTP_REQUEST->setPostVar("g-recaptcha-response","test");
		$err = $this->assertInvalid("test");
		$this->assertEquals("The test was not successful. Please try it again.",$err);
	}
}
