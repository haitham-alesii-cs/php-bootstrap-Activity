<?php
include 'connection.php';


if (isset($_POST['post'])) {
    $user_name = $_POST['username'];
    $user_number = $_POST['usernumber'];
    $user_email = $_POST['useremail'];

    $check_user = $conn->prepare("SELECT * FROM `users` WHERE user_email = :email Limit 1");
    $check_user->bindParam(':email', $user_email);
    $check_user->execute();

    if ($check_user->rowCount() > 0) {
        echo '<div class="alert alert-danger" role="alert">
                   إميل المستخدم موجود مسبقا  
                    </div>';
        header("refresh: 1");
    } else {
        $insert = $conn->prepare("INSERT INTO `users` (user_name,user_number,user_email) VALUES (:user_name,:numbers,:email);");
        $insert->bindParam(':user_name', $user_name);
        $insert->bindParam(':numbers', $user_number);
        $insert->bindParam(':email', $user_email);
        $insert->execute();
        echo '<div class="alert alert-success" role="alert">
                تم اضافة مستخدم جديد
                </div>';
        header("refresh: 1");
    }
}
if (isset($_POST['delete'])) {
    $user_id = $_POST['delete_id'];


    $check_user = $conn->prepare("SELECT * FROM `users` WHERE id = :user_id Limit 1");
    $check_user->bindParam(':user_id', $user_id);
    $check_user->execute();

    if ($check_user->rowCount() > 0) {
        $delete = $conn->prepare("DELETE FROM users WHERE id = :user_id");
        $delete->bindParam(':user_id', $user_id);
        $delete->execute();
        echo '<div class="alert alert-success" role="alert">
                تم حذف المستخدم بنجاح 
                </div>';
        header("refresh: 1");
    } else {
        echo '<div class="alert alert-danger" role="alert">
                    لم يتم العثور على المستخدم 
                    </div>';
        header("refresh: 1");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
</head>

<body style="background: #eee70;">
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">

                
            
                
            </a>
        </div>
    </nav>
    <div class="center" style="text-align: left; margin:30px; margin-top: 40px;">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"
                data-bs-whatever="@getbootstrap">إضافة مستخدم جديد</button>
            <button type="button" class="btn btn-danger" style="margin-left: 15px;" data-bs-toggle="modal"
                data-bs-target="#staticBackdrop">
                حذف مستخدم
            </button>
        </div>

    
    <div class="container" style="margin-top: 50px;">
        <?php
        $view = $conn->prepare("SELECT * FROM `users` ");
        $view->execute();
        if ($view->rowCount() > 0) {
        ?>
        <table class="table table-bordered border-primary">
            <thead class="table-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">User Name</th>
                    <th scope="col">User Number</th>
                    <th scope="col">User Email</th>
                </tr>
            </thead>

            <tbody>
                <?php
                    while ($fitch_user = $view->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                <tr>
                    <th scope="row"><?= $fitch_user['id']; ?></th>
                    <td><?= $fitch_user['user_name']; ?></td>
                    <td><?= $fitch_user['user_number']; ?></td>
                    <td><?= $fitch_user['user_email']; ?></td>

                </tr>
                <?php } ?>
            </tbody>

        </table>
        <?php
        } else {
            echo '<p class="empty" style="text-align: center;">لا يوجد عملاء بعد !</p>';
        }
        ?>


        <!-- Button trigger modal -->
        <div>

            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Delete User</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="row g-3 needs-validation" novalidate method="POST">

                                <div class="mb-3">
                                    <label for="id" class="form-label">Enter User ID</label>
                                    <div class="input-group has-validation">
                                        <input type="number" maxlength="10" class="form-control" name="delete_id"
                                            id="id" aria-describedby="inputGroupPrepend" required>
                                        <div class="invalid-feedback">
                                            Please Enter User ID.
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" 
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" name="delete">Delete</button>
                                    </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">New User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="row g-3 needs-validation" novalidate method="POST">
                                <div class="mb-3">
                                    <label for="validationCustomUsername" class="form-label">User Name</label>
                                    <div class="input-group has-validation">
                                        <input type="text" class="form-control" name="username"
                                            id="validationCustomUsername" aria-describedby="inputGroupPrepend" required>
                                        <div class="invalid-feedback">
                                            Please Enter a username.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="number-name" class="col-form-label">User Number</label>
                                        <div class="input-group has-validation">
                                            <input type="tel" class="form-control" id="number-name"
                                                aria-describedby="inputGroupPrepend" name="usernumber" required>
                                            <div class="invalid-feedback">
                                                Please Enter User Number.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="recipient-name" class="col-form-label">User Email</label>
                                            <div class="input-group has-validation">
                                                <input type="email" class="form-control" id="recipient-name"
                                                    aria-describedby="inputGroupPrepend" name="useremail" required>
                                                <div class="invalid-feedback">
                                                    Please Enter User Email.
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" 
                                            data-bs-dismiss="modal">Close</button>
                                                <button class="btn btn-primary" name="post" type="submit">ADD
                                                    USERS</button>
                                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
    (() => {


        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
    })()
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"
        integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"
        integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous">
    </script>
</body>

</html>