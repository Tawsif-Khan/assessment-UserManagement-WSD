<?php

if (isset($_GET['action']) && $_GET['action'] == 'logout') {

    Session::destroy();
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addUser'])) {

    $response = $userController->store($_POST);
} elseif (isset($_GET['remove'])) {

    $remove = preg_replace('/[^a-zA-Z0-9-]/', '', (int) $_GET['remove']);
    $response = $userController->delete($remove);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateUser'])) {

    $response = $userController->update($_GET['id'], $_POST);
}

if (isset($_GET['search']) && $_GET['keyword'] !== '') {

    $keyword = $_GET['keyword'];
    $users = $userController->search($keyword);
} else {

    $users = $userController->index();
}

if (isset($response)) {

    echo $response['message'];
}

// echo Session::get('msg') ?? '';
// Session::set('msg', null);
