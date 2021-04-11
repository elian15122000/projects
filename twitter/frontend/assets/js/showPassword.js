function showPassword() {
    let passwort = document.getElementById("passwort");
    let passwort2 = document.getElementById("passwortWdh");

    if (passwort.type == "password" || passwort2.type == "password") {
        passwort.type = "text";
        passwort2.type = "text";
    } else if (passwort.type == "text" && passwort2.type == "text") {
        passwort.type = "password";
        passwort2.type = "password";
    } else {
        passwort.type == "password";
        passwort2.type == "password";
    }
}


function showPasswordLogin() {
    let passwort = document.getElementById("passwort");

    if (passwort.type == "password") {
        passwort.type = "text";
    } else if (passwort.type == "text") {
        passwort.type = "password";
    } else {
        passwort.type == "password";
    }
}