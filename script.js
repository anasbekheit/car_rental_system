//TODO: CREDIT CARD,COUNTRY (TO BE APPLIED IN DB)
//TODO: FROM-TO yeb2o calendars
//TODO: CHECK THAT THE FIRST AND LAST NAME ARE VALID (ne7awel feha PHP) (TO BE APPLIED IN DB)

function validateRegisterForm() {
    //making sure all fields are not null
    /*
    let fname = document.forms["registerForm"]["fname"].value;
    if (fname == "") {
        alert("First name must be filled out");
        return false;
    }
    let lname = document.forms["registerForm"]["lname"].value;
    if (lname == "") {
        alert("Last name must be filled out");
        return false;
    }
    let country = document.forms["registerForm"]["country"].value;
    if (country == "") {
        alert("Country field must be filled out");
        return false;
    }
    let creditcard = document.forms["registerForm"]["creditcard"].value;
    if (creditcard == "") {
        alert("Credit card field must be filled out");
        return false;
    }
    let email = document.forms["registerForm"]["email"].value;
    if (email == "") {
        alert("Email must be filled out");
        return false;
    }
    let password = document.forms["registerForm"]["password"].value;
    if (password == "") {
        alert("Password must be filled out");
        return false;
    }
    let confirmPassword = document.forms["registerForm"]["confirmPassword"].value;
    if (confirmPassword == "") {
        alert("Please confirm your password");
        return false;
    }

    //making sure password matches confirmed password
    if (password != confirmPassword) {
        alert("Password doesn't match\nPlease re-enter your password");
        return false;
    }*/

    //making sure email has the right format
    let format = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if(!format.test(String(email).toLowerCase()))
    {
        alert("You have entered an invalid email address");
        return false;
    }
    //making sure password is 8-15 characters including at least 1 small letter, 1 capital letter, one digit and one special character
    /*let passFormat=  /^(?=.*\d)(?=.*[a-z])(?=.*[^a-zA-Z0-9]).{8,15}$/;
    if(!passFormat.test(String(password)))
    {
    alert("Password must be 8-15 characters\nIt must contain:\nOne upper case character\nOne lower case\nOne digit\nOne special character");
    return false;
    }*/

}

function validateLoginForm() {
    //making sure email field is not null
    let email = document.forms["loginForm"]["loginEmail"].value;
    if (email == "") {
        alert("Email must be filled out");
        return false;
    }
    //making sure password field is not null
    let password = document.forms["loginForm"]["loginPassword"].value;
    if (password == "") {
        alert("Password must be filled out");
        return false;
        
    }
}



