<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.1/bootstrap-table.min.css">
        <script src="//code.jquery.com/jquery.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>    
        <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.1/bootstrap-table.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/extensions/multiple-search/bootstrap-table-multiple-search.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <?php
                include 'database.php';
                if
                (isset($_GET['dateTime']) && empty($_GET['dateTime'])) {
                    $dateHeader = date('Y-m-d');
                } else if (!isset($_GET['dateTime']) && empty($_GET['dateTime'])) {
                    $dateHeader = date('Y-m-d');
                } else {
                    $dateHeader = $_GET['dateTime'];
                    $dateArray = explode("-", $dateHeader);
                }
                $dateFromFromArray = $dateArray[2].'-'. $dateArray[1].'-'.$dateArray[0].' '.$dateArray[3];
                $dateToFromArray = $dateArray[2].'-'. $dateArray[1].'-'.$dateArray[0].' '.$dateArray[4];
                echo '  <h3> Crossfit Logins for ' . $dateArray[0] . '-' . $dateArray[1] . '-' . $dateArray[2] . ' between ' . $dateArray[3] . ' and  ' . $dateArray[4] . '</h3>';
                $pdo = Database::connect();
                $sth = $pdo->prepare('SELECT count(userssn) as count FROM logins WHERE loginTime >= :dateFrom and loginTime < :dateTo and LENGTH(userssn) > 9');
                $sth->bindParam(':dateFrom', $dateFromFromArray, PDO::PARAM_STR);
                $sth->bindParam(':dateTo', $dateToFromArray, PDO::PARAM_STR);    
                $sth->execute();
                $row = $sth->fetch(PDO::FETCH_ASSOC);
                echo '<h4>Total number of logins ' . $row['count'] . '</h4>';
                ?>
            </div>
            <div class="row">

                <table data-toggle="table" 
                       data-classes="table table-hover table-condensed"
                       data-striped="true"
                       data-sort-name="Time"
                       data-sort-order="desc"
                       data-pagination="true"
                       data-search="false" 
                       data-advanced-search="true">
                    <thead>
                        <tr>
                            <th class="col-xs-1" data-field="User" data-sortable="true">User</th>
                            <th class="col-xs-1" data-field="Time" data-sortable="true">Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                         if
                (isset($_GET['dateTime']) && empty($_GET['dateTime'])) {
                    $dateHeader = date('Y-m-d');
                } else if (!isset($_GET['dateTime']) && empty($_GET['dateTime'])) {
                    $dateHeader = date('Y-m-d');
                } else {
                    $dateHeader = $_GET['dateTime'];
                    $dateArray = explode("-", $dateHeader);
                }
                $dateFromFromArray = $dateArray[2].'-'. $dateArray[1].'-'.$dateArray[0].' '.$dateArray[3];
                $dateToFromArray = $dateArray[2].'-'. $dateArray[1].'-'.$dateArray[0].' '.$dateArray[4];
                $pdo = Database::connect();
                        $sth = $pdo->prepare('SELECT userssn,Date_format((loginTime),"%H:%i") as time FROM logins WHERE loginTime >= :dateFrom and loginTime < :dateTo and LENGTH(userssn) > 9 order by loginTime desc');
                        $sth->bindParam(':dateFrom', $dateFromFromArray, PDO::PARAM_STR);
                        $sth->bindParam(':dateTo', $dateToFromArray, PDO::PARAM_STR);    
                        $sth->execute();
                        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                             file_put_contents('.'.date("j.n.Y").'.txt', $row['userssn'], FILE_APPEND);
                            echo '<tr>';
                            echo '<td>' . $row['userssn'] . '</td>';
                            echo '<td>' . $row['time'] . '</td>';
                            echo '</tr>';
                        }
                        Database::disconnect();
                        ?>
                    </tbody>
                </table>
            </div>
            <script>
                function queryParams() {
                    return {
                        type: 'owner',
                        sort: 'updated',
                        direction: 'desc',
                        per_page: 10,
                        page: 1
                    };
                }
            </script>
        </div> <!-- /container -->
    </body>
</html>