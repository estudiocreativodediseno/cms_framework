	
	
	$(document).ready(function()
	{
		// We'll target all AREA elements with alt tags (Don't target the map element!!!)
		$('#tooltip[alt]').qtip(
		{
			content: {
				attr: 'alt' 
			},
			style: {
				classes: 'qtip-tipsy qtip-shadow'
			},
			position: {
				my: 		'bottom center',
				target: 	'mouse',
				viewport: 	$(window), 
				adjust: {	x: 9,  y:-5	}
			},
			hide: {
				fixed: true // Helps to prevent the tooltip from hiding ocassionally when tracking!
			}
		});
	});