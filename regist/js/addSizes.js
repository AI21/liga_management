jQuery(function($){
	
	// addSizes was written by Natalie Downe 
	// http://natbat.net/2008/Aug/27/addSizes/
	
	// Copyright (c) 2008, Natalie Downe under the BSD license
	// http://www.opensource.org/licenses/bsd-license.php
	
	$('a[href$=".pdf"], a[href$=".doc"], a[href$=".mp3"], a[href$=".jpg"], a[href$=".png"], a[href$=".swf"], a[href$=".zip"], a[href$=".ogg"], a[href$=".m4u"]').each(function(){
		// looking at the href of the link, if it contains pdf, doc, zip, mp3, ogg, m4u, jpg, png, swf
		var link = $(this);
		var bits = this.href.split('.');
		var type = bits[bits.length -1];
		
		var url= "http://json-head.appspot.com/?url="+encodeURIComponent (this.href)+"&callback=?";
	
		// then call the json thing and insert the size back into the link text
		 $.getJSON(url, function(json){
			if(json.ok && json.headers['Content-Length']) {
				var length = parseInt(json.headers['Content-Length'], 10);
				
				// divide the length into its largest unit
				var units = [
					[1024 * 1024 * 1024, 'GB'],
					[1024 * 1024, 'MB'],
					[1024, 'KB'],
					[1, 'bytes']
				];
				
				for(var i = 0; i < units.length; i++){
					
					var unitSize = units[i][0];
					var unitText = units[i][1];
					
					if (length >= unitSize) {
						length = length / unitSize;
						// 1 decimal place
						length = Math.ceil(length * 10) / 10;
						var lengthUnits = unitText;
						break;
					}
				}
				
				// insert the text directly after the link and add a class to the link
				// note: if you want to insert the size into the link rather than after it change the following 'after' to 'append'
				link.after(' (' + type + ' ' + length + ' ' + lengthUnits + ')');
				link.addClass(type);
			}
		});
	});
});