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

function getStatusBadge($done, $rejected){
    if($done == 1)
        return '<span class="badge badge-success">Done !</span>';
    if($rejected == 1)
        return '<span class="badge badge-danger">Rejected !</span>';
    return "";
}