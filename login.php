<?php

define('APP_START', true);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';

requireGuest();

if (isPost()) {

    requireCsrf();

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {

        error('Username and password are required.');

    } else {

        if (login($username, $password)) {

            success('Welcome back.');

            redirect(
                BASE_URL . 'index.php'
            );

        }

        error(
            'Invalid username or password.'
        );

    }

}

$pageTitle = 'Admin Login';
?>

<!doctype html>

<html lang="en">

<head>

<meta charset="utf-8">

<meta
name="viewport"
content="width=device-width,initial-scale=1">

<title>

<?=APP_NAME?> Login

</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
rel="stylesheet">

<style>

body{

background:#f5f5f5;

display:flex;

align-items:center;

justify-content:center;

height:100vh;

}

.login-card{

width:100%;

max-width:420px;

border:0;

box-shadow:0 0 30px rgba(0,0,0,.08);

}

</style>

</head>

<body>

<div class="card login-card">

<div class="card-body p-4">

<h3
class="text-center mb-4">

<?=APP_NAME?>

</h3>

<?php if($msg=getSuccess()): ?>

<div class="alert alert-success">

<?=$msg?>

</div>

<?php endif; ?>

<?php if($msg=getError()): ?>

<div class="alert alert-danger">

<?=$msg?>

</div>

<?php endif; ?>

<form method="post">

<?=csrfField()?>

<div class="mb-3">

<label class="form-label">

Username

</label>

<input
type="text"
name="username"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">

Password

</label>

<input
type="password"
name="password"
class="form-control"
required>

</div>

<button
class="btn btn-primary w-100">

<i class="bi bi-box-arrow-in-right"></i>

Login

</button>

</form>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

document.addEventListener(
    'DOMContentLoaded',
    function(){

        const username =
            document.querySelector(
                'input[name="username"]'
            );

        if(username){
            username.focus();
        }

        document
            .querySelectorAll('.alert')
            .forEach(function(alert){

                setTimeout(function(){

                    bootstrap.Alert
                        .getOrCreateInstance(alert)
                        .close();

                },5000);

            });

    }
);

const passwordInput =
    document.querySelector(
        'input[name="password"]'
    );

if(passwordInput){

    const wrapper =
        passwordInput.parentElement;

    wrapper.insertAdjacentHTML(

        'beforeend',

        `
        <button
            type="button"
            id="togglePassword"
            class="btn btn-outline-secondary position-absolute"
            style="
                right:10px;
                top:38px;
            ">
            <i class="bi bi-eye"></i>
        </button>
        `
    );

    wrapper.classList.add(
        'position-relative'
    );

    document
        .getElementById(
            'togglePassword'
        )
        .addEventListener(
            'click',
            function(){

                if(
                    passwordInput.type ===
                    'password'
                ){

                    passwordInput.type =
                        'text';

                    this.innerHTML =
                        '<i class="bi bi-eye-slash"></i>';

                }else{

                    passwordInput.type =
                        'password';

                    this.innerHTML =
                        '<i class="bi bi-eye"></i>';

                }

            }
        );

}

document.addEventListener(
    'keydown',
    function(e){

        if(e.key === 'Enter'){

            const form =
                document.querySelector(
                    'form'
                );

            if(form){
                form.submit();
            }

        }

    }
);

</script>

</body>

</html>