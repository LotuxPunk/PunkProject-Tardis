<!doctype html>
<html lang="en">
    <head>
        <title><?= $title ?></title>
        <meta charset="utf-8"/>
        <link href="./views/style/style.css" rel="stylesheet" type="text/css">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta property="og:title" content="<?= $title ?>" />
        <meta property="og:description" content="<?= $desc ?>"/>
        <meta property="og:site_name" content="PunkProject" />
        <meta property="og:url" content="https://punkproject.xyz/" />
        <meta property="og:image" content="https://punkproject.xyz/views/img/logo.png" />
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">        <!-- Bootstrap CSS-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <!-- Quill -->
        <link href="https://cdn.quilljs.com/1.0.0/quill.snow.css" rel="stylesheet">
    </head>
    <body class="bg-dark">
        <div class="sticky-top">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark stroke">
                <a class="navbar-brand" href="index.php">PunkProject</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <?= $nav ?>
                        <li class="nav-item"><a class="nav-link" data-toggle="modal" data-target="#addRequest" href="#">Add a suggestion</a></li>
                        <?php if(isset($_SESSION["username"])){?><li class="nav-item"><a class="nav-link" href="#">My profile (<?= $_SESSION['username'] ?>)</a></li><?php } ?>
                    </ul>
                    <?php if(isset($_SESSION['connected'])) { ?>
                        <a class="float-right text-light" href="index.php?p=logout">Log out</a>
                    <?php } else { ?>
                        <a class="float-right text-light" href="index.php?p=login">Signup / Login</a>
                    <?php } ?>
                </div>
            </nav>
        </div>
        <div id="global" class="container-fluid">
            <?= $content ?>
        </div>
        <?= $modal ?>
        <?php if(isset($_SESSION['connected'])){?>
            <div class="modal fade" id="addRequest" tabindex="-1" role="dialog" aria-labelledby="addRequestLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addRequestLabel">New suggestion</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <form action="index.php?p=add-request" method="post">
                                <div class="form-group">
                                    <label for="title" class="col-form-label">Title:</label>
                                    <input type="text" name="title" class="form-control" id="title">
                                </div>
                                <div class="form-group">
                                    <label for="description" class="col-form-label">Description:</label>
                                    <div id="toolbar">
                                        <button class="ql-bold">Bold</button>
                                        <button class="ql-italic">Italic</button>
                                        <button class="ql-underline">Underline</button>
                                    </div>
                                    <div id="editor"></div>
                                    <textarea class="form-control" name="description" style="display:none" id="description"></textarea>

                                </div>
                                    <button type="submit" id="submit_request" class="btn btn-primary">Propose</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php } else { ?>
            <div class="modal fade" id="addRequest" tabindex="-1" role="dialog" aria-labelledby="addRequestLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addRequestLabel">It's embarassing...</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body bg-info"><p>You must be logged in to share your ideas!</p><img src="https://media.giphy.com/media/WyJLAIJYPfMfC/giphy.gif" alt="" style="width:80%;"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <!-- Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>

        <!-- Quill -->
        <script src="https://cdn.quilljs.com/1.0.0/quill.js"></script>
        <script src="./views/js/main.js"></script>
        <script>
            var editor = new Quill('#editor', {
                modules: { toolbar: '#toolbar' },
                theme: 'snow'
            });

            document.getElementById("submit_request").onclick = () => {
                document.getElementById("description").innerHTML = document.querySelector(".ql-editor").innerHTML;
            }
        </script>
    </body>
</html>