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
                <h3>Crossfit Logins</h3>
            </div>
            <div class="row">
                <div>
                    <a href="monthly.php" class="btn btn-info" role="button">Monthly Overview</a>
                    <a href="weekly.php" class="btn btn-info" role="button">Weekly Overview</a>
                    <a href="daily.php" class="btn btn-info" role="button">daily Overview</a>
                </div>
                <table data-toggle="table" 
                       data-classes="table table-hover table-condensed"
                       data-striped="true"
                       data-sort-name="User"
                       data-sort-order="desc"
                       data-pagination="true"
                       data-search="true" 
                       data-advanced-search="true">
                    <thead>
                        <tr>
                            <th class="col-xs-1" data-field="User" data-sortable="true">User</th>
                            <th class="col-xs-1" data-field="Time" data-sortable="true">Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'database.php';
                        $pdo = Database::connect();
                        $sql = 'select * from (SELECT COUNT(*) AS reports_in_week,DATE_ADD(loginTime, INTERVAL(1-DAYOFWEEK(loginTime)) DAY) as startTime,
                                DATE_ADD(loginTime, INTERVAL(7-DAYOFWEEK(loginTime)) DAY) as endTime
                                FROM logins GROUP BY WEEK(loginTime)) as t WHERE MONTH(t.startTime) = MONTH(NOW())';
                        foreach ($pdo->query($sql) as $row) {
                            echo '<tr>';
                            echo '<td><a href="read.php?id=' . $row['userssn'] . '">' . $row['userssn'] . '</a></td>';
                            echo '<td>' . $row['loginTime'] . '</td>';
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