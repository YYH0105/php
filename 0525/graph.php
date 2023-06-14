<?php
$link=@mysqli_connect('localhost','root','','chart');
    
mysqli_set_charset($link, "utf8");
$SQL='SELECT * FROM pie';
$result=mysqli_query($link, $SQL);
?>




<html>
    <meta charset='utf-8'>
    <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['地區', '人數比例']
          <?php      
          while($row=mysqli_fetch_assoc($result)){
            echo ",['";
            echo $row['Name'];
            echo "', ";
            echo $row['Value'];
            echo "]";
          }
          ?>
          
        ]);

        var options = {
          title: '各地區人口比例圓餅圖',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>

    <script type="text/javascript">
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawStacked);
  
    function drawAxisTickColors() {
        var data = google.visualization.arrayToDataTable([
            ['地區', '人口數'],
            <?php
                $result=mysqli_query($link, $SQL);
                while($row=mysqli_fetch_assoc($result)){
                    echo ",['";
                    echo $row['Name'];
                    echo "', ";
                    echo $row['Value'];
                    echo "],";
                }
            ?>
            
      ]);

      var options = {
          title: '各地區人口數(萬)',
        };

      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
    //第二個圖表另放新檔案
    </script>
    </head>
  <body>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
    <div id="chart_div" style="width: 900px; height: 500px;"></div>
  </body>
</html>
