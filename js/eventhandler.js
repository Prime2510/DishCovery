function validateScreenName(screenName) {
	let screenNameRegEx = /^[a-zA-Z0-9_]+$/;

	if (screenNameRegEx.test(screenName))
		return true;
	else
		return false;
}

function validatePWD(pwd) {
	let pwdRegEx = /^\S{6,}$/;
	if (pwdRegEx.test(pwd))
		return true;
	else
		return false;
}

function validateEmail(email) {
	// simple email pattern
	let emailRegEx = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

	if (emailRegEx.test(email))
		return true;
	else
		return false;
}



function validateAvatar(avatar) {
	let avatarRegEx = /^[^\n]+\.[a-zA-Z]{3,4}$/;

	if (avatarRegEx.test(avatar))
		return true;
	else
		return false;
}

function validateLogin(event) {

	// changed from "username" to "email"
	let email = document.getElementById("email");

	let password = document.getElementById("pwd");

	let formIsValid = true;

	// use validateEmail instead of validateUsername
	if (!validateEmail(email.value)) {
		// Comment the line below
		console.log("'" + email.value + "' is not a valid email");
		//	To Do 7a: ADD your code to dynamically add a class name to <input> tag to highlight the input box.	
		email.classList.add("input-error");

		//	To Do 7c: ADD your code to dynamically remove a class name to <p> tag to show the error message.	
		document.getElementById("error-text-email").classList.remove("hidden");

		formIsValid = false;
	}
	//	An else block to remove the error messages and the styles when the input field passes the validation 
	else {

		//	To Do 7b: ADD your code to dynamically remove a class name from the <input> tag to remove the highlights from the input box. 
		email.classList.remove("input-error");

		//	To Do 7d: ADD your code to dynamically add a class name from the <p> tag to hide the error message.	
		document.getElementById("error-text-email").classList.add("hidden");

	}

	if (!validatePWD(password.value)) {
		// Comment the line below
		console.log("'" + password.value + "' is not a valid password");
		//	To Do 7a: ADD your code to dynamically add a class name to <input> tag to highlight the input box.	
		password.classList.add("input-error");

		//	To Do 7c: ADD your code to dynamically remove a class name to <p> tag to show the error message.	
		document.getElementById("error-text-password").classList.remove("hidden");
		formIsValid = false;
	}
	//	An else block to remove the error messages and the styles when the input field passes the validation 
	else {

		//	To Do 7b: ADD your code to dynamically remove a class name from the <input> tag to remove the highlights from the input box. 
		password.classList.remove("input-error");

		//	To Do 7d: ADD your code to dynamically add a class name from the <p> tag to hide the error message.	
		document.getElementById("error-text-password").classList.add("hidden");

	}


	if (formIsValid === false) {
		event.preventDefault();

	}
	else {
		console.log("Validation successful, sending data to the server");
	}
}

function validateSignup(event) {
	// Use getElementById() to access the signup form's input elements
	// and store them in easy to remember variables.
	let emailRegEx = document.getElementById("email");
	let pwd = document.getElementById("pwd");
	let sname = document.getElementById("sname");
	let avatar = document.getElementById("profilephoto");
	let cpwd = document.getElementById("cpassword");

	let formIsValid = true;
	

	// Validate first name
	if (!validateScreenName(sname.value)) {
		formIsValid = false;
	}


	// Validate username
	if (!validateEmail(email.value)) {
		formIsValid = false;
	}

	// Validate password
	if (!validatePWD(pwd.value)) {
		formIsValid = false;
	}

	// Validate confirm password matches password
	if (pwd.value !== cpwd.value) {
		formIsValid = false;
	}


	// Validate avatar
	if (!validateAvatar(avatar.value)) {
		formIsValid = false;
	}

	if (formIsValid === false) {
		
		// If any of the validations fail, we need to stop the form submission.
		event.preventDefault();
		console.log("form validation failed, not sending data to the server");
	}
	else {
		console.log("validation successful, sending data to the server");
	}
}

function nameHandler(event) {
	let name = event.target;
	if (!validateScreenName(name.value)) {
		console.log("Screen name '" + name.value + "' is not valid.");

		name.classList.add("input-error");

		document.getElementById("error-text-sname").classList.remove("hidden");
	}
	else {
		console.log("Screen name is valid.");

		name.classList.remove("input-error");

		document.getElementById("error-text-sname").classList.add("hidden");

	}

}

function emailHandler(event) {
	let email = event.target;

	if (!validateEmail(email.value)) {
		// Comment the line below
		console.log("Email '" + email.value + "' is not valid.");
		//	To Do 8a: ADD your code to dynamically add a class name to <input> tag to highlight the input box.	
		email.classList.add("input-error");

		//	To Do 8c: ADD your code to dynamically remove a class name to <p> tag to show the error message.	
		document.getElementById("error-text-email").classList.remove("hidden");

	}
	else {
		// Comment the line below
		console.log("Email is valid.");
		//	To Do 8b: ADD your code to dynamically remove a class name from the <input> tag to remove the highlights from the input box. 
		email.classList.remove("input-error");

		//	To Do 8d: ADD your code to dynamically add a class name from the <p> tag to hide the error message.	
		document.getElementById("error-text-email").classList.add("hidden");
	}
}

function cpwdHandler(event) {
	let pwd = document.getElementById("pwd");
	let cpwd = event.target;
	if (pwd.value !== cpwd.value) {
		console.log("Your passwords: " + pwd.value + " and " + cpwd.value + " do not match");

		cpwd.classList.add("input-error");

		//	To Do 8c: ADD your code to dynamically remove a class name to <p> tag to show the error message.	
		document.getElementById("error-text-cpassword").classList.remove("hidden");
	}
	else {
		console.log("Passwords match.");

		cpwd.classList.remove("input-error");

		document.getElementById("error-text-cpassword").classList.add("hidden");
	}
}


function avatarHandler(event) {
	let avatar = event.target;

	if (!validateAvatar(avatar.value)) {
		console.log("Avatar '" + avatar.value + "' is not valid.");

		avatar.classList.add("input-error");

		//	To Do 8c: ADD your code to dynamically remove a class name to <p> tag to show the error message.	
		document.getElementById("error-text-profilephoto").classList.remove("hidden");
	}
	else {
		console.log("Avatar is valid.");

		avatar.classList.remove("input-error");

		document.getElementById("error-text-profilephoto").classList.add("hidden");
	}
}

function passwordHandler(event) {
	let password = event.target;

	if (!validatePWD(password.value)) {
		console.log("Password '" + password.value + "' is not valid.");

		password.classList.add("input-error");

		document.getElementById("error-text-password").classList.remove("hidden");

	}
	else {
		console.log("Password is valid.");

		password.classList.remove("input-error");

		document.getElementById("error-text-password").classList.add("hidden");
	}
}

// Validate recipe name (non-blank and ≤ 256 chars)
function validateRecipeName(recipeName) {
	return recipeName.trim() !== "" && recipeName.length <= 256;
}

// Validate cooking note (non-blank and ≤ 1300 chars)
function validateCookingNote(note) {
	return note.trim() !== "" && note.length <= 1300;
}

// Recipe form validation
function validateCreateRecipe(event) {
	let recipeName = document.getElementById("recipeName");
	
	let formIsValid = true;
	
	if (!validateRecipeName(recipeName.value)) {
		// Show error
		console.log("Recipe name '" + recipeName.value + "' is not valid.");
		recipeName.classList.add("input-error");
		document.getElementById("error-text-recipeName").classList.remove("hidden");
		formIsValid = false;
	}
	else {
		console.log("Recipe name is valid.");
		recipeName.classList.remove("input-error");
		document.getElementById("error-text-recipeName").classList.add("hidden");
	}
	
	if (formIsValid === false) {
		event.preventDefault();
		console.log("Form validation failed, not sending data to the server");
	}
	else {
		console.log("Validation successful, sending data to the server");
	}
}

// Recipe name input handler
function recipeNameHandler(event) {
	let name = event.target;
	
	if (!validateRecipeName(name.value)) {
		console.log("Recipe name '" + name.value + "' is not valid.");
		name.classList.add("input-error");
		document.getElementById("error-text-recipeName").classList.remove("hidden");
	}
	else {
		console.log("Recipe name is valid.");
		name.classList.remove("input-error");
		document.getElementById("error-text-recipeName").classList.add("hidden");
	}
}

// Cooking note validation
function validateAddNote(event) {
	let note = document.getElementById("newNote");
	
	let formIsValid = true;
	
	if (!validateCookingNote(note.value)) {
		// Show error
		console.log("Cooking note is not valid.");
		note.classList.add("input-error");
		document.getElementById("error-text-note").classList.remove("hidden");
		formIsValid = false;
	}
	else {
		console.log("Cooking note is valid.");
		note.classList.remove("input-error");
		document.getElementById("error-text-note").classList.add("hidden");
	}
	
	if (formIsValid === false) {
		event.preventDefault();
		console.log("Form validation failed, not sending data to the server");
	}
	else {
		console.log("Validation successful, sending data to the server");
	}
}

// Cooking note input handler with character counter
function cookingNoteHandler(event) {
	let note = event.target;
	let charCount = document.getElementById("charCount");
	
	// Update character counter
	if (charCount) {
		let currentLength = note.value.length;
		let maxLength = 1300;
		charCount.textContent = currentLength + "/" + maxLength + " characters";
		
		// Change color if approaching limit
		if (currentLength > maxLength) {
			charCount.classList.add("char-limit-exceeded");
			charCount.classList.remove("char-limit-warning");
		} else if (currentLength > maxLength * 0.9) {
			charCount.classList.add("char-limit-warning");
			charCount.classList.remove("char-limit-exceeded");
		} else {
			charCount.classList.remove("char-limit-warning");
			charCount.classList.remove("char-limit-exceeded");
		}
	}
	
	if (!validateCookingNote(note.value)) {
		console.log("Cooking note is not valid.");
		note.classList.add("input-error");
		document.getElementById("error-text-note").classList.remove("hidden");
	}
	else {
		console.log("Cooking note is valid.");
		note.classList.remove("input-error");
		document.getElementById("error-text-note").classList.add("hidden");
	}
}
