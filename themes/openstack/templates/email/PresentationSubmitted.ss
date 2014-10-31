<html>
<body>
<% with Member %>
<p>Hello $FirstName $Surname --</p>
<% end_with %>

<p>Thank you for your presentation submission for the {$Summit.Name}. We’re excited and grateful that you took the time to submit a proposal. We’re carefully storing your submission for review by our community and our track chairs. Great content is essential to the success of our summits. Good luck in the selection process!</p>

<p><strong>SO WHAT HAPPENS NEXT?</strong><br/>
The call for presentations will remain open until {$Summit.AcceptSubmissionsEndDate.Month} {$Summit.AcceptSubmissionsEndDate.DayOfMonth}, {$Summit.AcceptSubmissionsEndDate.Year}. At that point, members of the OpenStack community will be encouraged to review the submissions and rate their interest. Ultimately, the OpenStack Summit Track Chairs will select the presentations that will be included in the final Summit agenda, using the results of the community voting as input. If your presentation is selected, you will be notified by the Summit speaker managers and receive a free registration code to attend the Summit. The speaker managers will be in contact with you via email to help coordinate your presentation and answer any questions you may have.</p>

<p><strong>WHEN WILL I BE NOTIFIED ABOUT MY SELECTION STATUS?</strong><br/>
Once the track chairs have made final selections for each track, we’ll send you an email with your status (whether you were selected to present, chosen as an alternate, or not included in this Summit). You should receive an email around the last week of August.</p>

<p><strong>CAN I STILL EDIT MY SUBMISSIONS OR ADD NEW ONES?</strong><br/>
Absolutely. You can continue to edit your submissions or add new ones until {$Summit.AcceptSubmissionsEndDate.Month} {$Summit.AcceptSubmissionsEndDate.DayOfMonth}.</p>

<p><strong>HOW CAN I SEE / EDIT MY SUBMISSIONS?</strong><br/>
Please go to https://www.openstack.org{$Link}.</p>

<p><strong>WHAT IF I DON’T KNOW MY PASSWORD?</strong><br/>
Your password can be reset here: https://www.openstack.org/Security/lostpassword</p>

<p><strong>WHAT IF I HAVE ADDITIONAL QUESTIONS?</strong><br/>
Please feel free to contact us at events@openstack.org</p>

<p>Thanks again! We look forward to seeing you at the {$Summit.Name}.</p>
<p>Sincerely,<br/>
The OpenStack Events Team</p>

</body>