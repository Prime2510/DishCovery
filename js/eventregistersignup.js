let form = document.getElementById("signupform");
form.addEventListener("submit", validateSignup);

let uname = document.getElementById("email");
uname.addEventListener("blur", emailHandler);

let cpwd = document.getElementById("cpassword");
cpwd.addEventListener("blur", cpwdHandler);

let avatar = document.getElementById("profilephoto");
avatar.addEventListener("blur", avatarHandler);

let name = document.getElementById("sname");
name.addEventListener("blur", nameHandler);

let password = document.getElementById("pwd");
password.addEventListener("blur", passwordHandler);
