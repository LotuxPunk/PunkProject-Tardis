let pBox = document.getElementById("password");
let pBox2 = document.getElementById("password2");
let buttonReset = document.getElementById("btnResetPwd");

pBox.onchange = checkPass;
pBox2.onchange = checkPass;

function checkPass(){
    // console.log(pBox.value.length >= 8 && pBox.value === pBox2.value);
    // console.log(pBox.value);
    // console.log(pBox2.value);
    // console.log(pBox.value === pBox2.value);
    // console.log(pBox.value.length >= 8);
    if(pBox.value.length >= 8 && pBox.value === pBox2.value){
        buttonReset.disabled = false;
        buttonReset.classList.remove("disabled");
    }
    else{
        buttonReset.disabled = true;
        buttonReset.classList.add("disabled");
    }
}