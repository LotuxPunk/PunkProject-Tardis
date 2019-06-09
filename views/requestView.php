<?php 
    require('viewsFunctions.php');
    $title = 'Requests - PunkProject';
    $desc = 'All requests for the NEW TARDIS MOD';
?>

<?php ob_start(); ?>
<li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
<li class="nav-item active"><a class="nav-link" href="index.php?p=request">All requests</a></li>
<li class="nav-item"><a class="nav-link" href="index.php?p=memberslist">Members list</a></li>
<li class="nav-item"><a class="nav-link" href="index.php?p=submissions">Assets submissions</a></li>
<?php $nav = ob_get_clean(); ?>

<?php ob_start(); ?>
<?php if($message != ""){?><div class="alert alert-info" role="alert"><?= $message ?></div><?php } ?>

<div class="jumbotron">
    <h2 class="display-4">Features requests</h2>
</div>
<div class="container">
    <nav>
        <ul class="pagination justify-content-center bg-dark">
            <li class="page-item"><a class="page-link" onclick="prevPage()" href="#">Previous</a></li>
            <li class="page-item"><span class="page-link page-number">1<span class="sr-only">(current)</span></span></li>
            <li class="page-item"><a class="page-link" onclick="nextPage()" href="#">Next</a></li>
        </ul>
    </nav>
</div>
<div class="bg-white rounded mb-3 request" id="requests">

</div>
<div class="container">
    <nav>
        <ul class="pagination justify-content-center">
            <li class="page-item"><a class="page-link" onclick="prevPage()" href="#">Previous</a></li>
            <li class="page-item"><span class="page-link page-number">1<span class="sr-only">(current)</span></span></li>
            <li class="page-item"><a class="page-link" onclick="nextPage()" href="#">Next</a></li>
        </ul>
    </nav>
</div>
<?php $content = ob_get_clean(); ?>

<?php ob_start(); ?>
<script>
    const requests = <?php echo $jsonRequests; ?>;
    const level =  <?php if(isset($_SESSION["level"])) echo $_SESSION["level"]; else echo 0; ?>;
    const requestsPerPage = 10;
    let block = document.getElementById("requests");
    let page = 1;
    let maxNumberPage = Math.floor(requests.length / requestsPerPage);

    if(requests.length % requestsPerPage > 0)
        maxNumberPage++;

    loadRequests();

    function nextPage(){
        if(page < maxNumberPage){
            page++;
        }
        loadRequests();
    }

    function prevPage(){
        if(page > 1){
            page--;
        }
        loadRequests();
    }

    function loadRequests(){
        block.innerHTML = "";
        refreshPageNumber();
        let subRequests = requests.slice((page - 1) * requestsPerPage, page * requestsPerPage);

        for (const request of subRequests) {
            let elem = document.createElement("div");
            elem.classList.add("border-bottom","row");

            let elemContent = document.createElement("div");
            elemContent.classList.add("col-9");
            elemContent.innerHTML = '<h5>' + request.title + '<small> by <a href="index.php?profile=' + request.idUser + '">' + request.username + '</a></small> ' + getStatusBadge(request.done, request.rejected, request.idDuplicate)+'</h5>';
            elem.appendChild(elemContent);

            let content = document.createElement("p");
            content.innerHTML = request.content;
            elemContent.appendChild(content);

            let btnbar = document.createElement("div");
            btnbar.classList.add("btn-group","admin-bar");
            btnbar.style = "margin-bottom:20px;"
            btnbar.setAttribute("role","group");
            let barHtml = "";
            if(level >= 5 && request.done == 0 && request.rejected == 0 && request.idDuplicate == 0)
                barHtml = "<a class='btn btn-success' role='button' href='index.php?done=" + request.id + "'><i class='fas fa-check'></i> Done</a><a class='btn btn-danger' href='index.php?rejected=" + request.id + "' role='button'><i class='fas fa-times-circle'></i> Reject</a><a href='index.php?delete=" + request.id + "' class='btn btn-danger' role='button'><i class='far fa-trash-alt'></i> Delete post</a><a href='index.php?ban=" + request.idUser + "' class='btn btn-danger' role='button'><i class='fas fa-gavel'></i> Ban user</a>";
            barHtml += '<a class="btn btn-secondary" role="button" href="index.php?p=focus&id=' + request.id + '"><i class="far fa-eye"></i> Focus</a></div></div></div>';
            btnbar.innerHTML = barHtml;
            elemContent.appendChild(btnbar);

            block.appendChild(elem);
        }
    }

    function refreshPageNumber(){
        var els = document.getElementsByClassName("page-number");
        
        Array.prototype.forEach.call(els, function(el) {
            el.innerHTML = page;
        });
    }
    
    function getStatusBadge(done, rejected, duplicate){
        if(done == 1){
            return '<span class="badge badge-success">Done</span>';
        }
        if(rejected == 1){
            return '<span class="badge badge-danger">Rejected</span>';
        }
        if(duplicate > 0){
            return '<span class="badge badge-primary">Duplicated</span> <a href="index.php?p=focus&id='+duplicate+'"><i class="fas fa-link"></i></a>';
        }
        return "";
    }
</script>
<?php $modal = ob_get_clean(); ?>

<?php require('template.php'); ?>