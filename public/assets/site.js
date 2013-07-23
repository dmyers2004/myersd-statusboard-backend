function trigger(url,id) {
	console.log('trigger',url,id);
	jQuery.get(url,function(d,t,j){jQuery('#section'+id).html(d);},'html');
}
