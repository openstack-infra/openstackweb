<h1>Edit Your Company's OpenStack Profile</h1>
<% if isCompanyAdmin %>

    $CompanyEditForm
    <script type="text/javascript">
        tinyMCE.init({
        theme : "advanced",
        mode: "exact",
        elements : "CompanyEditForm_CompanyEditForm_Description, CompanyEditForm_CompanyEditForm_Contributions, CompanyEditForm_CompanyEditForm_Products",
        theme_advanced_toolbar_location : "top",
        theme_advanced_buttons1 : "formatselect,|,bold,italic,underline,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,outdent,indent,separator,undo,redo",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
        height:"250px",
        width:"800px"
        });
    </script>
<% else %>
    <p>You must be logged in as someone with permission to edit this company.</p>
<% end_if %>
