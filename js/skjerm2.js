
// Hover
$(".slidemouseover").hover(function () {
		// Mouse in
		
		$.each( $(this).attr('class').split(' '), function(index, item){
			if (item.substr(0,8) ==='slidenum') {
			   // Found slidenum, making all slides with this num selected
			   // id = item.substr(8);
			   $('.slidenum'+item.substr(8)).addClass('slideselected');
			}
		});

	}, function () {
		// Mouse out
		
		$.each( $(this).attr('class').split(' '), function(index, item){
			if (item.substr(0,8) ==='slidenum') {
			   // Found slidenum, making all slides with this num selected
			   // id = item.substr(8);
			   $('.slidenum'+item.substr(8)).removeClass('slideselected');
			}
		});
		
	});