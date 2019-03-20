<?php

function getRoleBadge($level){
    if($level == 1)
        return '<span class="badge badge-success">Donator</span>';
    elseif($level == 5)
        return '<span class="badge badge-secondary">Team</span>';
    elseif($level == 9)
        return '<span class="badge badge-primary">Owner</span>';
    elseif($level == 10)
        return '<span class="badge badge-info">Admin</span>';
    return "";
}

function getStatusBadge($done, $rejected, $duplicate){
    if($done == 1)
        return '<span class="badge badge-success">Done</span>';
    if($rejected == 1)
        return '<span class="badge badge-danger">Rejected</span>';
    if($duplicate > 0){
        return '<span class="badge badge-primary">Duplicated</span> <a href="index.php?p=focus&id='.$duplicate.'"><i class="fas fa-link"></i></a>';
    }
    return "";
}

function getModeratorBar($id_post, $id_user){
    return "<a class='btn btn-success' role='button' href='index.php?done={$id_post}'><i class='fas fa-check'></i> Done</a><a class='btn btn-danger' href='index.php?rejected={$id_post}' role='button'><i class='fas fa-times-circle'></i> Reject</a><a href='index.php?delete={$id_post}' class='btn btn-danger' role='button'><i class='far fa-trash-alt'></i> Delete post</a><a href='index.php?ban={$id_user}' class='btn btn-danger' role='button'><i class='fas fa-gavel'></i> Ban user</a>";
}