<?php $title = 'PunkProject'; ?>

<?php ob_start(); ?>
<li class="nav-item active"><a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a></li>
<li class="nav-item"><a class="nav-link" data-toggle="modal" data-target="#addRequest" href="#">Add a suggestion</a></li>
<li class="nav-item"><a class="nav-link" href="#">Another useless link</a></li>
<?php $nav = ob_get_clean(); ?>

<?php ob_start(); ?>
<div class="card bg-light mb-3">
    <div class="card-body">
        <div class="d-flex">
            <img src="./views/img/logo.png" width="30" height="30" class="d-inline-block align-top" alt="">&nbsp;<h5 class="card-title">New TARDIS Mod</h5>
        </div>
        <p class="card-text">You have a suggestion, an idea, or a remark to improve the mod ? Go ahead, we're listening !</p>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addRequest">I have an idea !</button>
    </div>
</div>
<?php $content = ob_get_clean(); ?>

<?php ob_start(); ?>
<div class="modal fade" id="addRequest" tabindex="-1" role="dialog" aria-labelledby="addRequestLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRequestLabel">New suggestion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="title" class="col-form-label">Title:</label>
                        <input type="text" class="form-control" id="title">
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-form-label">Description:</label>
                        <textarea class="form-control" id="description"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Propose</button>
            </div>
        </div>
    </div>
</div>
<?php $modal = ob_get_clean(); ?>

<?php require('template.php'); ?>