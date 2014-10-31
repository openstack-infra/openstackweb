<% if GA_Data %>
<% with GA_Data %>
<!-- Google Code for HK_TICKET_ADWORDS Conversion Page -->
<script type="text/javascript">
    /* <![CDATA[ */

    var google_conversion_id       = $GAConversionId;
    var google_conversion_language = "{$GAConversionLanguage}";
    var google_conversion_format   = "{$GAConversionFormat}";
    var google_conversion_color    = "{$GAConversionColor}";
    var google_conversion_label    = "{$GAConversionLabel}";
    var google_conversion_value    = $GAConversionValue;
    var google_remarketing_only    = $GARemarketingOnly;

    /* ]]> */
</script>

<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
<noscript>
    <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/$GAConversionId/?value=$GAConversionValue&amp;label=$GAConversionLabel&amp;guid=ON&amp;script=0"/>
    </div>
</noscript>
<!-- End Google Code for HK_TICKET_ADWORDS Conversion Page -->
<% end_with %>
<% end_if %>
