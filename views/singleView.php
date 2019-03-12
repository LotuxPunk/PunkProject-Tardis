<?php
    require('viewsFunctions.php');
    $title = $row['title'];
    $desc = $row['content'];
?>

<?php ob_start(); ?>
<li class="nav-item"><a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a></li>
<li class="nav-item"><a class="nav-link" href="index.php?p=request&n=1">All requests</a></li>
<li class="nav-item"><a class="nav-link" href="index.php?p=memberslist">Members list</a></li>
<?php $nav = ob_get_clean(); ?>

<?php ob_start(); ?>
<?php if($message != ""){?><div class="alert alert-info" role="alert"><?= $message ?></div><?php } ?>
<div class="bg-white rounded mb-3 request">
    <?php
        $status = "";
        $vote = "";
        $moderation = "";
        $edit = "";
        
        if($row['done'] == 1){
            $status = ' <span class="badge badge-success">Done !</span>';
        }
        elseif($row['rejected'] == 1){
            $status = ' <span class="badge badge-danger">Rejected !</span>';
        }
        else{
            if($voted == 0){
                $vote = '<div class="float-right btn-group" style="margin-top:20px;" role="group"><a class="btn btn-success" role="button" href="index.php?up='.$row['id'].'"><i class="fas fa-thumbs-up"></i></a><span class="btn btn-secondary">'.$row['vote'].'</span><a class="btn btn-danger" href="index.php?down='.$row['id'].'" role="button"><i class="fas fa-thumbs-down"></i></a></div>';
            }
            elseif($voted == 1){
                $vote = '<div class="float-right btn-group" style="margin-top:20px;" role="group"><span class="btn btn-success"><i class="fas fa-thumbs-up"></i></span><span class="btn btn-secondary">'.$row['vote'].'</span><a class="btn btn-danger disabled" href="index.php?down='.$row['id'].'" role="button" aria-disabled="true"><i class="fas fa-thumbs-down"></i></a></div>';
            }
            else{
                $vote = '<div class="float-right btn-group" style="margin-top:20px;" role="group"><a class="btn btn-success disabled" href="index.php?up='.$row['id'].'" role="button" aria-disabled="true"><i class="fas fa-thumbs-up"></i></a><span class="btn btn-secondary">'.$row['vote'].'</span><span class="btn btn-danger"><i class="fas fa-thumbs-down"></i></span></div>';
            }

            if(isset($_SESSION['id']) && $_SESSION['id'] == $row['id_user']){
                $edit = "<a href='#' data-toggle='modal' data-target='#editRequest' class='btn btn-secondary' role='button'><i class='fas fa-pen'></i> Edit</a>";
            }

            if(isset($_SESSION['connected']) && $_SESSION['level'] >= 5){
                $moderation = getModeratorBar($row['id'], $row['id_user'])."<a class='btn btn-primary' href='#' data-toggle='modal' data-target='#duplicateModal'><i class='far fa-clone'></i> Duplicate</a>";
            }
        }
    ?>
    <h3><?= $row['title'] ?><small> by <?= $user ?></small><?= $status ?></h3>
    <?php 
        echo '<div class="row"><div class="col-9"><p>'.htmlspecialchars_decode($row['content']).'</p><div class="btn-group" style="margin-bottom:20px;" role="group">'.$moderation . $edit .'</div></div><div class="col-3">'.$vote.'</div></div>';
     ?>
</div>
<?php $content = ob_get_clean(); ?>

<?php ob_start(); ?>
<div class="modal fade" id="editRequest" tabindex="-1" role="dialog" aria-labelledby="editRequestLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRequestLabel">Edit suggestion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="index.php?p=edit-request&id=<?php echo $row['id']; ?>" method="post">
                    <div class="form-group">
                        <label for="title" class="col-form-label">Title:</label>
                        <input type="text" name="title" value="<?php echo $row['title'];?>" class="form-control" id="title">
                    </div>
                    <div class="form-group">
                        <label for="desc_edit" class="col-form-label">Description:</label>
                        <div id="editToolbar">
                            <button class="ql-bold">Bold</button>
                            <button class="ql-italic">Italic</button>
                            <button class="ql-underline">Underline</button>
                        </div>
                        <div id="editEditor"><?php echo htmlspecialchars_decode($row['content']);?></div>
                        <textarea class="form-control" name="desc_edit" style="display:none" id="desc_edit"></textarea>
                    </div>
                        <button type="submit" id="submit_edit_request" class="btn btn-primary">Confirm</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="duplicateModal" tabindex="-1" role="dialog" aria-labelledby="duplicateLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-Username" id="duplicateLabel">Duplicate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="index.php?p=duplicate" method="POST">
                    <div class="form-group">
                        <label for="postTitle">Posts</label>
                        <select class="form-control" id="postTitle">
                        <?php while($row = $postsData->fetch_assoc()){
                            echo "<option value='{$row['id']}>{$row['title']}</option>";
                        }?>
                        </select>
                    </div>
                    <button class="btn btn-primary" type="submit"><i class="fas fa-check"></i> Confirm</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById("submit_edit_request").onclick = () => {
        document.getElementById("desc_edit").innerHTML = document.querySelector(".ql-editor").innerHTML;
    }
</script>
<?php $modal = ob_get_clean(); ?>

<?php require('template.php'); ?>