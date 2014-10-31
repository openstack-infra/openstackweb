<?php
/**
 * Thanks to Dean Rather <dean@deanrather.com>
 *                       https://github.com/deanrather
 */
class SMTP_LogEmailWriter extends SS_LogEmailWriter {

	/**
	 * Send an email to the email address set in
	 * this writer.
	 */
	public function _write($event) {
		// If no formatter set up, use the default
		if(!$this->_formatter) {
			$formatter = new SS_LogErrorEmailFormatter();
			$this->setFormatter($formatter);
		}

		$formattedData = $this->_formatter->format($event);
		$subject = $formattedData['subject'];
		$data = $formattedData['data'];

		$email = new Email();
		$email->setTo($this->emailAddress);
		$email->setSubject($subject);
		$email->setBody($data);	
		$email->setFrom(self::$send_from);
		$email->send();
	}
}
?>
