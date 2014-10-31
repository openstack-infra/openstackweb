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


    jQuery(document).ready(function($) {

        var oldrecordOutboundLink = recordOutboundLink;
        recortboundLink        = function(){};

        $('.outbound-link').live('click',function(event){
            var href  = $(this).attr('href');
            var link = this;
            var image = new Image(1,1);
            image.src = "//www.googleadservices.com/pagead/conversion/{$GAConversionId}/?value={$GAConversionValue}&amp;label={$GAConversionLabel}&amp;guid=ON";
            image.onload = function(){
                oldrecordOutboundLink(link,'Outbound Links',href);
            };
            event.preventDefault();
            event.stopPropagation();
            return false;
        });
    });

    /* ]]> */
</script>

<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>

<!-- End Google Code for HK_TICKET_ADWORDS Conversion Page -->
<% end_with %>
<% end_if %>
