<?php
/**
 * Copyright 2014 Openstack.org
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * http://www.apache.org/licenses/LICENSE-2.0
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 **/
class UserStoriesHolder extends Page
{
	static $db = array();
	static $has_one = array();
	static $has_many = array();

	function getCMSFields()
	{
		$fields = parent::getCMSFields();
		return $fields;
	}

	static $allowed_children = array('OpenstackUser');
}

class UserStoriesHolder_Controller extends Page_Controller
{

	static $allowed_actions = array('pdf');

	public function pdf($request)
	{

		$open_stack_user_id = $request->param('ID');
		$open_stack_user = OpenstackUser::get()->byID($open_stack_user_id);
		$story = $open_stack_user->Children()->First();
		$file = FileUtils::convertToFileName($open_stack_user->Title) . '.pdf';
		$html_inner = $story->renderWith("UserStoryPage");
		$base = Director::baseFolder();
		$css = $base . "/themes/openstack/css/main.pdf.css";

		$html_outer = "<html><head>
					  <style>
					  " . str_replace("@host", $base, @file_get_contents($css)) . "
					  </style>
   					  </head><body><div class='container'>" . $html_inner . "</div></body></html>";
		try {
			$html2pdf = new HTML2PDF('P', 'A4', 'en', true, 'UTF-8', array(15, 5, 15, 5));
			$html2pdf->WriteHTML($html_outer);
			//clean output buffer
			ob_end_clean();
			$html2pdf->Output($file, "D");
		} catch (HTML2PDF_exception $e) {
			$message = array(
				'errno' => '',
				'errstr' => $e->__toString(),
				'errfile' => 'UserStoriesHolder.php',
				'errline' => '',
				'errcontext' => ''
			);
			SS_Log::log($message, SS_Log::ERR);
			$this->httpError(404,'There was an error on PDF generation!');
		}
	}

	function init()
	{
		parent::init();
	}

	function FeaturedCompaniesByDate($num = 4)
	{
		return OpenstackUser::get()->filter(array('FeaturedOnSite' => 1, 'ListedOnSite' => 1))->sort('Created')->limit($num);
	}

	function OtherCompaniesByDate($num = 10)
	{
		return OpenstackUser::get()->filter(array('FeaturedOnSite' => 0, 'ListedOnSite' => 1))->sort('Created')->limit($num);
	}


	function Form()
	{

		$NameField = new TextField('Name', 'Contact Name');
		$CompanyNameField = new TextField('Company', 'Company or Organization');
		$EmailField = new TextField('Email', 'Email Address');

		$fields = new FieldList(
			$NameField,
			$CompanyNameField,
			$EmailField
		);

		$submitButton = new FormAction('submitNewUserStory', 'Submit Form');
		$submitButton->addExtraClass('button');

		$actions = new FieldList(
			$submitButton
		);

		// Create Validators
		$validator = new RequiredFields('Name', 'Email', 'Company');

		// Form(controller, form name, fields, actions, validator)
		return new Form($this, 'Form', $fields, $actions, $validator);

	}

	function submitNewUserStory($data, $form)
	{

		$OpenStackUserRequest = new OpenStackUserRequest();
		$form->saveInto($OpenStackUserRequest);
		$OpenStackUserRequest->write();

		//Send email alert about submission
		$Subject = "New Website Feedback Submission";
		$email = EmailFactory::getInstance()->buildEmail(USER_STORIES_NEW_SUBMISSION_EMAIL_FROM, USER_STORIES_NEW_SUBMISSION_EMAIL_TO, $Subject);
		$email->setTemplate("OpenStackUserRequestEmail");
		$email->populateTemplate($OpenStackUserRequest);
		$email->send();

		// Redirect back to the page with a success message
		$form->controller->setMessage('Success', 'Thanks for letting us know you use OpenStack! We\'ll contact you soon about the possibility of being featured on this page.');
		$form->controller->redirectBack();
	}


}