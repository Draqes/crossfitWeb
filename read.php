<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <link   href="css/bootstrap.min.css" rel="stylesheet">
        <script src="js/bootstrap.min.js"></script>
    </head>

    <body>
        <div class="container">
            <div class="container">
                <div class="row">
                    <?php
                     if (!empty($_GET['id'])) {
                                $id = $_REQUEST['id'];
                            }

                            if (null == $id) {
                                header("Location: index.php");
                            } else {
                     echo '<h3>Crossfit Logins for ' .$id.'</h3>';
                            }
                      ?>  
                </div>
                <div class="row">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Tími</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include 'database.php';
                            $id = null;
                            if (!empty($_GET['id'])) {
                                $id = $_REQUEST['id'];
                            }

                            if (null == $id) {
                                header("Location: index.php");
                            } else {
                                $pdo = Database::connect();
                                $stmt = $pdo->prepare("SELECT loginTime FROM logins WHERE userssn=:id");
                                $stmt->execute(array(':id' => $id));
                                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($rows as $value) {
                                    echo '<tr>';
                                    echo '<td>' . $value['loginTime'] . '</td>';
                                    echo '</tr>';
                                }
                            }
                            Database::disconnect();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div> <!-- /container -->
    </body>
</html>