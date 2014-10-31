<% if FB_Data %>
<% with FB_Data %>
    <script type="text/javascript">
        var fb_param = {};
        fb_param.pixel_id = "{$FBPixelId}";
        fb_param.value = '{$FBValue}';
        fb_param.currency = '{$FBCurrency}';
        (function(){
            var fpw = document.createElement('script');
            fpw.async = true;
            fpw.src = '//connect.facebook.net/en_US/fp.js';
            var ref = document.getElementsByTagName('script')[0];
            ref.parentNode.insertBefore(fpw, ref);
        })();
    </script>
    <noscript>
        <img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/offsite_event.php?id={$FBPixelId}&amp;value={$FBValue}&amp;currency={$FBCurrency}" />
    </noscript>
<% end_with %>
<% end_if %>