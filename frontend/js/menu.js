$(document).ready(function() {
    get_menu();    
});

function get_menu() {
	$.ajax({
        url: "frontend/ajax/menu.php",  
        // data: {},
        success: function(data){   
            $('#content').html(data);
        } 
    });
}