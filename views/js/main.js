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

//TEST

let block = document.getElementById("requests");

    for (const request of requests) {
        //<a class="btn btn-secondary" role="button" href="index.php?p=focus&id='.$row['id'].'"><i class="far fa-eye"></i> Focus</a></div></div><div class="col-3">'.$vote.'</div></div>';
        let elem = document.createElement("div");
        elem.classList.add("border-bottom","row");

        let elemContent = document.createElement("div");
        elemContent.classList.add("col-9");
        elemContent.innerHTML = '<h5>' + request.title + '<small> by <a href="index.php?profile=' + request.idUser + '">' + request.username + '</a></small></h5>'
        elem.appendChild(elemContent);

        let content = document.createElement("p");
        content.innerHTML = request.content;
        elemContent.appendChild(content);

        let btnbar = document.createElement("div");
        btnbar.classList.add("btn-group","admin-bar");
        btnbar.style = "margin-bottom:20px;"
        btnbar.setAttribute("role","group");
        let barHtml = "";
        if(level >= 5)
            barHtml = "<a class='btn btn-success' role='button' href='index.php?done="+request.id+"'><i class='fas fa-check'></i> Done</a><a class='btn btn-danger' href='index.php?rejected="+request.id+"' role='button'><i class='fas fa-times-circle'></i> Reject</a><a href='index.php?delete="+request.id+"' class='btn btn-danger' role='button'><i class='far fa-trash-alt'></i> Delete post</a><a href='index.php?ban="+request.idUser+"' class='btn btn-danger' role='button'><i class='fas fa-gavel'></i> Ban user</a>";
        barHtml += '<a class="btn btn-secondary" role="button" href="index.php?p=focus&id='+request.id+'"><i class="far fa-eye"></i> Focus</a></div></div><div class="col-3">'+request.vote+'</div></div>';
        btnbar.innerHTML = barHtml;
        btnbar.style.display = "block";
        elemContent.appendChild(btnbar);

        block.appendChild(elem);
    }