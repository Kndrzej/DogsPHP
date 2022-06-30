<!DOCTYPE html>

<head>
    <link rel="stylesheet" type="text/css" href="public/css/style.css">
    <title>DogsPHP</title>
</head>

<body>
<div class="container">
    <div class="logo">
        <img src="public/img/dog.jpg">
    </div>
    <div class="logo">
        <img src="public/img/dog.jpg">
    </div>
    <table>
        <tr>
            <th>id_user</th>
            <th>email</th>
            <th>name</th>
            <th>surname</th>
            <th>phone</th>
            <th>is_admin</th>
        </tr>
                <?php
                if(isset($users)){
                    foreach($users[0] as $user) {
                        echo '<tr>
                            <td>'.$user['id_user'].'</td>
                            <td>'.$user['email'].'</td>
                            <td>'.$user['name'].'</td>
                            <td>'.$user['surname'].'</td>
                            <td>'.$user['phone'].'</td>
                            <td>'.$user['is_admin'].'</td>
                        </tr>';
                    }
                }
                ?>
    </table>
</body>