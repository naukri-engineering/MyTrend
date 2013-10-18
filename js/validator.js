//http://jquery.bassistance.de/validate/demo/
$().ready(function() {
    $("#passwordForm").validate({
	onkeyup: false,
	rules: {
	    password: {
		required: true,
		minlength: 6
	    },
	    confirmPassword: {
		required: true,
		minlength: 6,
		//equalTo: "#password"
	    }
	},
	messages: {
	    password: {
		required: "Please provide a password",
		minlength: "Your password must be at least 6 characters long"
	    },
	    confirmPassword: {
		required: "Please provide a password",
		minlength: "Your password must be at least 6 characters long",
		//equalTo: "Please enter the same password as above"
	    }
	}
    });


    $("#passwordSuperAdmin").validate({
        onkeyup: false,
        rules: {
            sa_password: {
                required: true,
                minlength: 6
            },
            sa_c_password: {
                required: true,
                minlength: 6,
                //equalTo: "#password"
            }
        },
        messages: {
            sa_password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 6 characters long"
            },
            sa_c_password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 6 characters long",
                //equalTo: "Please enter the same password as above"
            }
        }
    });

    $("#passwordAdmin").validate({
        onkeyup: false,
        rules: {
            a_password: {
                required: true,
                minlength: 6
            },
            a_c_password: {
                required: true,
                minlength: 6,
                //equalTo: "#password"
            }
        },
        messages: {
            a_password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 6 characters long"
            },
            a_c_password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 6 characters long",
                //equalTo: "Please enter the same password as above"
            }
        }
    });

        $("#adduserForm").validate({
                onkeyup: false,
                rules: {
			username: {
			    required: true,
			    minlength: 6
			},
                        password: {
                                required: true,
                                minlength: 6
                        },
                        confirmPassword: {
                                required: true,
                                minlength: 6,
                                //equalTo: "#password"
                        }
                },
                messages: {
                        username: {
                                required: "Please provide a username",
                                minlength: "Your username must be at least 6 characters long"
                        },
                        password: {
                                required: "Please provide a password",
                                minlength: "Your password must be at least 6 characters long"
                        },
                        confirmPassword: {
                                required: "Please provide a password",
                                minlength: "Your password must be at least 6 characters long",
                                //equalTo: "Please enter the same password as above"
                        }
                }
        });

});
