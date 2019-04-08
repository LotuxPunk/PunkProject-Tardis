let pBox = document.getElementById("password");
let pBox2 = document.getElementById("password2");
let buttonConfirm = document.getElementById("btnConfirm");

pBox.onchange = checkPass;
pBox2.onchange = checkPass;

function checkPass(){
    if(pBox.value.length >= 8 && pBox.value === pBox2.value){
        buttonConfirm.disabled = false;
        buttonConfirm.classList.remove("disabled");
    }
    else{
        buttonConfirm.disabled = true;
        buttonConfirm.classList.add("disabled");
    }
}