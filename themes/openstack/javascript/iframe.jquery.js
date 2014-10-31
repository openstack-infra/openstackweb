/**
 *  JQuery iFrame plugin for converting a link into an iframe.
 *  @author:  Vlada Misic / http://www.lucidcrew.com
 *  @version: 1.0
 *  http://33rockers.com/2006/12/05/unobtrusive-iframe-with-jquery
 *
 *  Thanks to M. Alsup http://www.malsup.com for his SWF plugin that was the basis for this code
 *
 *  This plugin converts anchor tags into iframes.
 *
 *
 *
 *  Sample HTML:
 *      before:  <a href="myIframe.html" class="iframe">My Iframe Link</a>
 * 
 *      after:   <div class=iframe>
 					<iframe id=content_iframe marginWidth=0 marginHeight=0 src="myIframe.html" frameBorder=0 width=640 height=480>
					</iframe>
				</div>
 *
 *  Usage:
 *      $('a.iframe').iframe();
 *
 *
 *
 *  Notes:
 *  -----
 *
 *  Options are passed to the 'flash' function using a single Object.  The options
 *  Object is a hash of key/value pairs.  The following option keys are supported:
 *
 *  Options:
 *  -------
 *  width:      	width of iframe (default: 640)			w:640
 *  height:      	height of iframe (default: 480)			h:480		
 *  scrolling:   	auto									sc:auto
 *  frameborder:	height of iframe (default: 0)			fb:0	
 *  marginwidth:	margin of iframe (default: 0)			wm:0		
 *  marginheight:	margin of iframe (default: 0)			hm:0	
 *
 *  * height, width, version and background values can be embedded in the classname using the following syntax:
 *    <a class="iframe w:450 h:450 scr:no"></a>
 */
 
 jQuery.fn.iframe = function(options) {
    return this.each(function() {
        var $this = jQuery(this);
        var cls = this.className;
        
        var opts = jQuery.extend({
            frameborder:  ((cls.match(/fb:(\d+)/)||[])[1]) || 0,
            marginwidth:  ((cls.match(/wm:(\d+)/)||[])[1]) || 0,
            marginheight: ((cls.match(/hm:(\d+)/)||[])[1]) || 0,
            width:        ((cls.match(/w:(\d+)/)||[])[1]) || 640,
            height:       ((cls.match(/h:(\d+)/)||[])[1]) || 750,
            scrolling:    ((cls.match(/sc:(\w+)/)||[])[1]) || "auto",
            version:     '1,0,0,0',
            cls:          cls,
            src:          $this.attr('href') || $this.attr('src'),
			id:			  $this.attr('id'),
            caption:      $this.attr('text'),
            attrs:        {},
            elementType:  'div',
            xhtml:        true
        }, options || {});
        
        var endTag = opts.xhtml ? ' />' : '>';

        var a = ['<iframe src="' + opts.src + '"'];
		if(opts.id){
			a.push(' id="' + opts.id + '"');
		}else{
			a.push(' id="content_iframe"');
		}
		a.push(' frameborder="' + opts.frameborder + '"');
		a.push(' marginwidth="' + opts.marginwidth + '"');
		a.push(' marginheight="' + opts.marginheight + '"');
		a.push(' width="100%"');
		a.push(' height="' + opts.height + '"');
		a.push(' scrolling="' + opts.scrolling + '"');
		a.push(endTag);
        
        // convert anchor to span/div/whatever...
        var $el = jQuery('<' + opts.elementType + ' class="' + opts.cls + '"></' + opts.elementType + '>');
        $this.after($el).remove();
        $el.html(a.join(''));
		//if(opts.caption){
		//	$el.append('<br' + endTag + opts.caption);
		//}
		//alert($el.html());
    });
};