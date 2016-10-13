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
                <?php
                $ssn = $_POST['ssn'];
                echo('<h3>Crossfit Logins for user '.$ssn.'</h3>');
                ?>
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
                            <th class="col-xs-1" data-field="loginTimes" data-sortable="true">Login Times</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $ssn = $_POST['ssn'];
                        $month = intval(date("n"));
                        include 'database.php';
                        include 'fusioncharts.php';
                        $pdo = Database::connect();
                        $sth = $pdo->prepare('select * from logins where userssn = :ssn');
                        $sth->bindParam(':ssn', $ssn, PDO::PARAM_STR);
                        $sth->execute();
                        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tr>';
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