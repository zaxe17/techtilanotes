/* LOGIN FORM SCRIPT */
function myMenuFunction() {
    var i = document.getElementById("navMenu");

    if(i.className === "nav-menu") {
        i.className += " responsive";
    } 
    else {
        i.className = "nav-menu";
    }
}

var a = document.getElementById("loginBtn");
var b = document.getElementById("registerBtn");
var log = document.getElementById("login");
var reg = document.getElementById("register");
var fp = document.getElementById("forgot");

function login() {
    log.style.left = "5px";
    reg.style.right = "-520px";
    fp.style.right = "520px"
    a.className += " white-btn";
    b.className = "btn";
    log.style.opacity = 1;
    reg.style.opacity = 0;
    document.title = "Techtilanotes | Login";
}

function register() {
    fp.style.right = "520px";
    log.style.left = "-510px";
    reg.style.right = "5px";
    a.className = "btn";
    b.className += " white-btn";
    log.style.opacity = 0;
    reg.style.opacity = 1;
    document.title = "Techtilanotes | Register";
}

function forgot() {
    log.style.left = "510px";
    fp.style.right = "5px";
    log.style.opacity = 0;
    fp.style.opacity = 1;
    document.title = "Techtilanotes | Forgot Password";
}

/* SOUND */
const audio = new Audio("./sound/chicken_sound.mp3"); // FILE SOUND
const maxTimeInSeconds = 1;
let startTime = 0;

function playAudio() {
    audio.currentTime = 0;
    audio.play();

    setTimeout(checkElapsedTime, 100);
}

function checkElapsedTime() {
    if(!audio.paused && audio.currentTime - startTime >= maxTimeInSeconds) {
        audio.pause();
    } 
    else {
        setTimeout(checkElapsedTime, 100);
    }
}

/* NOTES */
const addBox = document.querySelector(".add-box"), // BUTTON TO SHOW THE FORM
    popupBox = document.querySelector(".popup-box"),
    popupTitle = popupBox.querySelector("header p"),
    closeIcon = popupBox.querySelector("header i"),
    titleTag = popupBox.querySelector("input"), // TITLE
    descTag = popupBox.querySelector("textarea"); // CONTENT

const addUpdateInput = document.getElementById("add_update"); // INPUT BUTTON
var noteForm = document.getElementById("noteForm"); // FORM FOR ADD AND UPDATE

/* POP-UP FORM */
/* ADD NOTES */
addBox.addEventListener("click", () => {
    playAudio(); // AUDIO
    popupTitle.innerText = "Add a New Note"; // BOX TITLE OF ADD NOTE
    addUpdateInput.value = "Add Note"; // TEXT FOR INPUT BUTTON
    noteForm.setAttribute("action", "./query/add.php"); // ADD NOTE FILE
    addUpdateInput.setAttribute("name", "submit"); // NAME OF BUTTON FOR THE ADD BUTTON
    popupBox.classList.add("show");
    document.querySelector("body").style.overflow = "hidden";
    if (window.innerWidth > 660) titleTag.focus();
});

/* CLOSE BUTTON */
closeIcon.addEventListener("click", () => {
    titleTag.value = descTag.value = "";
    popupBox.classList.remove("show"); // REMOVE THE CLASS NAME FROM POPUP BOX
    document.querySelector("body").style.overflow = "auto";
});

/* UPDATE NOTES */
function updateNote(noteId, title, filterDesc) {
    // REPLACE ALL OCCURANCES OF <br/> WITH NEWLINE CHARACTERS
    let description = filterDesc.replaceAll('<br/>', '\n');
    addBox.click();
    titleTag.value = title;
    descTag.value = description;
    popupTitle.innerText = "Note";
    addUpdateInput.value = "Save Note";
    addUpdateInput.setAttribute("name", "submit"); // NAME OF BUTTON FOR THE UPDATE BUTTON
    noteForm.setAttribute("action", "./query/update.php"); // UPDATE FILE 
    document.getElementById("note_id").value = noteId; // THIS WILL GET THE NOTE_ID PER NOTE TO UPDATE THE NOTE  
}

/* SHOW SETTINGS/MENU */
function showMenu(elem) {
    elem.parentElement.classList.add("show"); // ADD THE CLASS NAME
    document.addEventListener("click", e => {
        if (e.target.tagName != "I" || e.target != elem) {
            elem.parentElement.classList.remove("show"); // REMOVE CLASS NAME
        }
    });
}
