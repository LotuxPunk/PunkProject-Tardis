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
<div class="bg-white rounded mb-3 request" id="requests">
    
</div>
<?php $content = ob_get_clean(); ?>

<?php ob_start(); ?>
<script>
    let requests = <?php echo $jsonRequests; ?>;
    let block = document.getElementById("requests");

    for (const request of requests) {
        //'<p>'.htmlspecialchars_decode($row['content']).'</p><div class="btn-group admin-bar" style="margin-bottom:20px;" role="group">'.$moderation.'<a class="btn btn-secondary" role="button" href="index.php?p=focus&id='.$row['id'].'"><i class="far fa-eye"></i> Focus</a></div></div><div class="col-3">'.$vote.'</div></div>';
        let elem = document.createElement("div");
        elem.classList.add("border-bottom","row");

        let elemContent = document.createElement("div");
        elemContent.classList.add("col-9");
        elemContent.innerHTML = '<h5>' + request.title + '<small> by <a href="index.php?profile=' + request.idUser + '">' + request.username + '</a></small></h5>'
        elem.appendChild(elemContent);

        let content = document.createElement("p");
        content.innerHTML = request.content;
        elemContent.appendChild(content);
        
        block.appendChild(elem);
    }
</script>
<?php $modal = ob_get_clean(); ?>

<?php require('template.php'); ?>