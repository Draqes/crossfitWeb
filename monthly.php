<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.1/bootstrap-table.min.css">
        <script src="//code.jquery.com/jquery.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>    
        <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.1/bootstrap-table.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/extensions/multiple-search/bootstrap-table-multiple-search.js"></script>
        <script type="text/javascript" src="http://static.fusioncharts.com/code/latest/fusioncharts.js"></script>
        <script type="text/javascript" src="http://static.fusioncharts.com/code/latest/fusioncharts.widgets.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <h3>Crossfit Logins</h3>
            </div>
            <div class="row">
                <div>

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
                            <th class="col-xs-1" data-field="count" data-sortable="true">Number of logins</th>
                            <th class="col-xs-1" data-field="firstDay" data-sortable="true">First day of week</th>
                            <th class="col-xs-1" data-field="lastDay" data-sortable="true">Last day of week</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $ssn = $_POST['ssn'];
                        $month = intval(date("n"));
                        include 'database.php';
                        include 'fusioncharts.php';
                        $pdo = Database::connect();
                        $sth = $pdo->prepare('select * from (SELECT COUNT(*) AS reports_in_week,DATE_ADD(loginTime, INTERVAL(2-DAYOFWEEK(loginTime)) DAY) as startTime,
                                DATE_ADD(loginTime, INTERVAL(8-DAYOFWEEK(loginTime)) DAY) as endTime
                                FROM logins GROUP BY WEEK(loginTime)) as t WHERE MONTH(t.startTime) = :now');
                        $sth->bindParam(':now', $month, PDO::PARAM_INT);
                        $sth->execute();
                        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tr>';
                            echo '<td>' . $row['reports_in_week'] . '</td>';
                            echo '<td>' . $row['startTime'] . '</td>';
                            echo '<td>' . $row['endTime'] . '</td>';
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