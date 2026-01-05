let form = document.getElementById("recipe-form");
form.addEventListener("submit", validateCreateRecipe);

let recipeName = document.getElementById("recipeName");
recipeName.addEventListener("blur", recipeNameHandler);