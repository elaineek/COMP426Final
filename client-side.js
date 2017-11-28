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
		    alert('Login Successful'); },
		error: function () {
		    alert('Login Failed');}
	       });
    });

    $('#logout_form').on('submit', function (e) {
	e.stopPropagation();
	e.preventDefault();
	$.ajax('logout.php',
	       {type: 'GET',
		cache: false,
		successful: function () {
		    alert("Logged Out");
		}
	       });
    });

    $('#register_form').on('submit', 
    		function(e) {
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
		    			console.log(error, status, jqXHR);}

		    		});
    });

    $('#secret_form').on('submit', function (e) {
	e.stopPropagation();
	e.preventDefault();

	$.ajax('secret.php',
	       {type: 'GET',
		cache: false,
		success: function (data, status, jqxhr) {
		    alert(data);
		},
		error: function(jqxhr, status, error) {
		    alert("Couldn't get secret");
		}
	       });
    });
});
