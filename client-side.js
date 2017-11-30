var url_base = "..";

$(document).ready(function () {

    $('#login_form').on('submit', function (e) {
	e.stopPropagation();
	e.preventDefault();
	$.ajax('login.php',
	       {type: 'GET',
		data: $('#login_form').serialize(),
		cache: false,
		success: function () {
		    alert('Login Successful'); 
			$('#login_form')[0].reset();},
		error: function (jqXHR, status, error) {
		    alert(jqXHR.responseText);
			$('#login_form')[0].reset();}
	       });
    });

    $('#register_form').on('submit', 
    		function(e) {
    		e.stopPropagation();
   			e.preventDefault();
		    $.ajax("register.php",
		    		{type: 'POST',
		    		dataType: "json",
		    		data: $(this).serialize(),
		    		success: function(user_json, status, jqXHR) {
		    			var u = new Users(user_json);
		    			alert('Registration Successful!');
		    			$('#register_form')[0].reset();
		    		},
		    		error: function(jqXHR, status, error) {
		    			alert(jqXHR.responseText);
		    			$('#register_form')[0].reset();}

		    		});
    });

});
