<?php 
    require('viewsFunctions.php');
    $title = 'Requests - PunkProject';
    $desc = 'All requests for the NEW TARDIS MOD - Page '. $page;
?>

<?php ob_start(); ?>
<li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
<li class="nav-item active"><a class="nav-link" href="index.php?p=request&n=1">All requests</a></li>
<li class="nav-item"><a class="nav-link" href="index.php?p=memberslist">Members list</a></li>
<li class="nav-item"><a class="nav-link" href="index.php?p=submissions">Assets submissions</a></li>
<?php $nav = ob_get_clean(); ?>

<?php ob_start(); ?>
<?php if($message != ""){?><div class="alert alert-info" role="alert"><?= $message ?></div><?php } ?>
<?php if(isset($_SESSION["showdone"])){?>
<div class="bg-white rounded mb-3 request">
    <form action="index.php?p=showdone" method="post">
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" name="showdone" id="showdone" <?php echo ($_SESSION["showdone"])? "checked" : "";?>>
            <label class="form-check-label" for="showdone">Show implemented suggestion ?</label>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<?php } ?>
<div class="bg-white rounded mb-3 request">
    <h3>Page <?= $page ?></h3>
    <?php $i = 0; 
    $vote = "";
    while($row = $request->fetch_assoc()){
        $status = getStatusBadge($row['done'], $row['rejected'], $row['id_duplicate']);
        if($status != ""){
            echo '<div class="border-bottom row"><div class="col-12"><h5>'.$row['title'].'<small> by <a href="index.php?profile='.$row['id_user'].'">'.$row['username'].'</a></small> '.$status.'</h5><p>'.htmlspecialchars_decode($row['content']).'</p></div></div>';
        }
        else{
            if($voted[$i] == 0){
                $vote = '<div class="float-right btn-group" style="margin-top:20px;" role="group"><a class="btn btn-success" role="button" href="index.php?up='.$row['id'].'"><i class="fas fa-thumbs-up"></i></a><span class="btn btn-secondary">'.$row['vote'].'</span><a class="btn btn-danger" href="index.php?down='.$row['id'].'" role="button"><i class="fas fa-thumbs-down"></i></a></div>';
            }
            elseif($voted[$i] == 1){
                $vote = '<div class="float-right btn-group" style="margin-top:20px;" role="group"><span class="btn btn-success"><i class="fas fa-thumbs-up"></i></span><span class="btn btn-secondary">'.$row['vote'].'</span><a class="btn btn-danger disabled" href="index.php?down='.$row['id'].'" role="button" aria-disabled="true"><i class="fas fa-thumbs-down"></i></a></div>';
            }
            else{
                $vote = '<div class="float-right btn-group" style="margin-top:20px;" role="group"><a class="btn btn-success disabled" href="index.php?up='.$row['id'].'" role="button" aria-disabled="true"><i class="fas fa-thumbs-up"></i></a><span class="btn btn-secondary">'.$row['vote'].'</span><span class="btn btn-danger"><i class="fas fa-thumbs-down"></i></span></div>';
            }
    
            $moderation = "";
            if(isset($_SESSION['connected']) && $_SESSION['level'] >= 5){
                $moderation = getModeratorBar($row['id'], $row['id_user']);
            }
            echo '<div class="border-bottom row"><div class="col-9"><h5>'.$row['title'].'<small> by <a href="index.php?profile='.$row['id_user'].'">'.$row['username'].'</a></small></h5><p>'.htmlspecialchars_decode($row['content']).'</p><div class="btn-group admin-bar" style="margin-bottom:20px;" role="group">'.$moderation.'<a class="btn btn-secondary" role="button" href="index.php?p=focus&id='.$row['id'].'"><i class="far fa-eye"></i> Focus</a></div></div><div class="col-3">'.$vote.'</div></div>';
            $i++;
        }
    } ?>
    <nav>
        <ul class="pagination">
            <?php
                if($page > 1){
                    $prev_page = $page - 1;
                    echo '<li class="page-item"><a class="page-link" href="index.php?p=request&n='.$prev_page.'">Previous</a></li>';
                }

                for ($i = 1; $i <= $nb_pages; $i++){
                    echo '<li class="page-item"><a class="page-link" href="index.php?p=request&n='.$i.'">'.$i.'</a></li>';
                }

                if ($page < $nb_pages) {
                    $next_page = $page + 1;
                    echo '<li class="page-item"><a class="page-link" href="index.php?p=request&n='.$next_page.'">Next</a></li>';
                }
            ?>
        </ul>
    </nav>
</div>
<?php $content = ob_get_clean(); ?>

<?php ob_start(); ?>

<?php $modal = ob_get_clean(); ?>

<?php require('template.php'); ?>