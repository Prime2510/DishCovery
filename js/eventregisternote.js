let form = document.getElementById("note-form");
form.addEventListener("submit", validateAddNote);

let newNote = document.getElementById("newNote");
newNote.addEventListener("input", cookingNoteHandler);
newNote.addEventListener("blur", cookingNoteHandler);