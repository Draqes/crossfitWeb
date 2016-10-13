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
        <script>
function clearForms()
{
  var i;
  for (i = 0; (i < document.forms.length); i++) {
    document.forms[i].reset();
  }
}
</script>
    </head>
    <body onLoad="clearForms()" onUnload="clearForms()">
        <div class="container">

            <div class="row">

                <?php
                include 'database.php';
                if
                (isset($_GET['date']) && empty($_GET['date'])) {
                    $dateHeader = date('Y-m-d');
                } else if (!isset($_GET['date']) && empty($_GET['date'])) {
                    $dateHeader = date('Y-m-d');
                } else {
                    $dateHeader = $_GET['date'];
                }
                echo '  <h3> Crossfit Logins for ' . $dateHeader . '</h3>';
                echo '<form method="post" action="user.php" class="well,span4 pull-right">';
                echo '<input type="text" name="ssn" class="span3" placeholder="Type user ssn"> ';
                echo '<button type="submit" class="btn">Submit</button>  ';
                echo'</form>';

                $pdo = Database::connect();
                $sth = $pdo->prepare('SELECT count(userssn) as count from logins where DATE(loginTime) = DATE(:date) and LENGTH(userssn) > 9');
                $sth->bindParam(':date', $dateHeader, PDO::PARAM_STR);
                $sth->execute();
                $row = $sth->fetch(PDO::FETCH_ASSOC);
                echo '<h4>Total number of logins ' . $row['count'] . '</h4>';
                if ($dateHeader == date('Y-m-d')) {
                    echo '<a href="index.php?date=' . date('Y-m-d', strtotime($dateHeader . ' -1 day')) . '" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-arrow-left"></span></a> ';
                } else {
                    echo '<a href="index.php?date=' . date('Y-m-d', strtotime($dateHeader . ' -1 day')) . '" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-arrow-left"></span></a> <a href="index.php?date=' . date('Y-m-d', strtotime($dateHeader . ' 1 day')) . '" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-arrow-right"></span></a>';
                }
                ?>
            </div>

            <div class="row">
                <div class="row-fluid" id="chart-1"><!-- Fusion Charts will render here--></div>
                <table data-toggle="table" 
                       data-classes="table table-hover table-condensed"
                       data-striped="true"
                       data-sort-name="User"
                       data-sort-order="desc"
                       data-pagination="true"
                       data-search="false" 
                       data-advanced-search="true">
                    <thead>
                        <tr>
                            <th class="col-xs-1" data-field="logins" data-sortable="true">Number of logins</th>
                            <th class="col-xs-1" data-field="hour" data-sortable="true">Hour</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'fusioncharts.php';
                        $arrData = array(
                            "chart" => array(
                                "xAxisName" => "Hours",
                                "yAxisName" => "logins",
                                "caption" => "Logins Per Hour",
                                "paletteColors" => "#0075c2",
                                "bgColor" => "#ffffff",
                                "borderAlpha" => "20",
                                "canvasBorderAlpha" => "0",
                                "usePlotGradientColor" => "0",
                                "plotBorderAlpha" => "10",
                                "showXAxisLine" => "1",
                                "xAxisLineColor" => "#999999",
                                "showValues" => "0",
                                "divlineColor" => "#999999",
                                "divLineIsDashed" => "1",
                                "showAlternateHGridColor" => "0"
                            )
                        );

                        $arrData["data"] = array();

                        if
                        (isset($_GET['date']) && empty($_GET['date'])) {
                            $date = date('Y-m-d H:i:s');
                        } else if (!isset($_GET['date']) && empty($_GET['date'])) {
                            $date = date('Y-m-d H:i:s');
                        } else {
                            $dateString = $_GET['date'];
                            $timestamp = strtotime($dateString);
                            $date = date('Y-m-d H:i:s', $timestamp);
                        }
                        $pdo = Database::connect();
                        $sth = $pdo->prepare('SELECT Concat(Date_format( From_unixtime( Floor( Unix_timestamp(logintime) / 3600 ) * 3600 ) , "%H:%i" ),
                                             "-",Date_format( From_unixtime( Ceiling(Unix_timestamp(logintime) / 3600) * 3600 ),"%H:%i")) AS timestamp_hour, count(userssn) AS logins FROM logins WHERE date (logintime) = date(:date) and LENGTH(userssn) > 9 GROUP BY timestamp_hour');
                        $sth->bindParam(':date', $date, PDO::PARAM_STR);
                        $sth->execute();
                        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tr>';
                            echo '<td>' . $row['logins'] . '</td>';
                            echo '<td><a href="dailyDetails.php?dateTime=' . date("d-m-Y", strtotime($date)) . '-' . $row['timestamp_hour'] . '">' . $row['timestamp_hour'] . '</a></td>';
                            echo '</tr>';
                            array_push($arrData["data"], array("label" => $row["timestamp_hour"], "value" => $row["logins"]));
                        }
                        $jsonEncodedData = json_encode($arrData);
                        Database::disconnect();
                        $columnChart = new FusionCharts("Column2D", "myFirstChart", "100%", 300, "chart-1", "json", $jsonEncodedData);
                        $columnChart->render();
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
                        per_page: 25,
                        page: 1
                    };
                }
            </script>
        </div> <!-- /container -->
    </body>
</html>