<h2>Speaking Submissions since November 1, 2012 &mdash; $SpeakingSubmissionCount total</h2>

<table>
    <tr>
        <th>Presenter</th>
        <th>Title</th>
        <th>Topic</th>
        <th>Date Submitted</th>
    </tr>
<% loop SpeakingSubmissions %>
    <tr>
	    <td style="vertical-align: top;padding-bottom:25px;"><nobr><strong>$FirstName $LastName</strong></nobr></td>
	    <td style="vertical-align: top;padding-bottom:25px;">
	        <strong>$PresentationTitle</strong><br/>
	        $Abstract
	    </td>
        <td style="vertical-align: top;padding-bottom:25px;">$Topic</td>
	    <td style="vertical-align: top;padding-bottom:25px;"><nobr>$Created.format("M d&#44; Y")</nobr></td>
    </tr>
<% end_loop %>
</table>
