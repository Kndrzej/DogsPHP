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
                    print_r($users);
                    foreach($users as $user) {
                        echo '<tr>
                            <td>'.$user[0].'</td>
                            <td>'.$user[1].'</td>
                            <td>'.$user[2].'</td>
                            <td>'.$user[3].'</td>
                            <td>'.$user[4].'</td>
                            <td>'.$user[5].'</td>
                        </tr>';
                    }
                }
                ?>
    </table>
</body>