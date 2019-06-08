window.onresize = () => {
    initBtnGroup();
};

window.onload = () => {
    initBtnGroup();
}

function initBtnGroup(){
    const classesSmallDevices = ["btn-group-vertical"];
    const classesRegularDevices = ["btn-group"];

    //MENU
    let btngroup = document.getElementById("btn-group-home-jumbotron");
    if(btngroup != null){
        if(window.innerWidth < 800){
            btngroup.classList.remove(classesRegularDevices);
            btngroup.classList.add(classesSmallDevices);
        }
        else{
            btngroup.classList.remove(classesSmallDevices);
            btngroup.classList.add(classesRegularDevices);
        }
    }
    

    //ADMIN BAR
    let adminBars = document.getElementsByClassName("admin-bar");
    if(adminBars.length > 0){
        if(window.innerWidth < 800){
            for (const element of adminBars) {
                element.classList.remove(classesRegularDevices);
                element.classList.add(classesSmallDevices);
            }
        }
        else{
            for (const element of adminBars) {
                element.classList.remove(classesSmallDevices);
                element.classList.add(classesRegularDevices);
            }
        }
    }
}

function toogleButtonGroup(element, isSmall){
    if(isSmall){
        element.classList.remove("btn-group");
        element.classList.add(classesSmallDevices);
    }
    else{
        element.classList.remove(classesSmallDevices);
        element.classList.add("btn-group");
    }
}
