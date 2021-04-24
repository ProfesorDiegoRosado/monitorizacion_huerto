<!doctype html>

<html lang="es">
<head>
  <!-- Required meta tags for Bootstrap 4 -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <!-- custom CSS -->
  <link rel="stylesheet" type="text/css" href="css/custom.css">

  <!-- Font awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha256-UzFD2WYH2U1dQpKDjjZK72VtPeWP50NoJjd26rnAdUI=" crossorigin="anonymous">
  <!-- Ubuntu font -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Ubuntu:regular,bold&subset=Latin">
  <!-- Open Sans font -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

  <?php
    // require php files
    require_once 'custom_functions.php';
    require_once 'sqlite_php.php';
    // add favicon
    add_favicon();
  ?>



  <title>Monitorización huerto - Recolección de datos</title>
  <meta name="description" content="Recolección y muestra de datos de monitorización del huerto de IES Arroyo de la Miel">
  <meta name="author" content="IES Arroyo de la Miel">

  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</head>

<body>

  <div class="container" id="principal_container">

    <!-- cabecera html huerto -->
    <?php require_once 'html_header.php'; ?>

    <hr/>

    <div id="data_information_div">
      <h1 class="title_1 text_shadow_2">Monitorización Ambiental</h1>
      <p class="text_description">Resultados de los datos de monitorización ambiental del huerto del IES Arroyo de la Miel.</p>
    </div>

    <div id="data_to_collect">
      <p>Los datos a recoletar son:</p>
      <ul>
        <li>humedad</li>
        <li>temperatura</li>
        <li>presión</li>
        <li>humedad de la tierra</li>
      </ul>
    </div>

    <!-- Data to collect  -->
    <?php
      // temperatura
      $temperature_curve = read_magnitude("BMP180","temperatura");
      //print_r($temperature_curve);

      // Presion
      $presion_curve = read_magnitude("BMP180","presion");
      //print_r($presion_curve);

      // Hunedad del terreno
      $soil_humidity_curve = read_magnitude("DZ0325","humedad_terreno");
      //print_r($soil_humidity_curve);

      // Humedad
      $humidity_curve = read_magnitude("DHT11","humedad");
      //print_r($humidity_curve);

      // temperatura DHT11
      //$temperature_curve = read_magnitude("DHT11","temperatura");
      //print_r($temperature_curve);
    ?>

    <!-- Aggregated data for charting generation -->
    <?php

      // Aggregated array with generation for chart display
      function mapping_array($hour, $data) {
        return array(strval($hour) . ":00", $data);
      }

      // Data array temperature
      $temperature_data_array = array_map("mapping_array", array_keys($temperature_curve), $temperature_curve);

      // Data array Presion
      $presion_data_array = array_map("mapping_array", array_keys($presion_curve), $presion_curve);

      // Data array soil humidity
      $soil_humidity_data_array = array_map("mapping_array", array_keys($soil_humidity_curve), $soil_humidity_curve);

      // Data array temperature
      $humidity_data_array = array_map("mapping_array", array_keys($humidity_curve), $humidity_curve);

      /*
      print("<br><br>humidity_data_array:<br>");
      print_r($humidity_data_array);
      */
    ?>

    <div >
      <h2 class="title_2 text_shadow_1">Curva de Temperatura</h2>
      <p class="text_description">Curva de temperatura ambiente leída por el sensor BMP180.</p>

      <div id="temperature_chart_div"></div>
    </div>

    <div >
      <h2 class="title_2 text_shadow_1">Curva de Presión Atmosférica</h2>
      <p class="text_description">Curva de presión atmosférica leída por el sensor BMP180.</p>

      <div id="presion_chart_div"></div>
    </div>

    <div >
      <h2 class="title_2 text_shadow_1">Curva de Humedad del terreno</h2>
      <p class="text_description">Curva de humedad del terreno leída por el sensor DZ0325.</p>

      <div id="soil_humidity_chart_div"></div>
    </div>

    <div >
      <h2 class="title_2 text_shadow_1">Curva de Humedad</h2>
      <p class="text_description">Curva de humedad ambiente leída por el sensor DHT11.</p>

      <div id="humidity_chart_div"></div>
    </div>


    <?php require 'footer.php'; ?>


    <!-- Scripts google chart -->
    <!--Load google charts-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <!-- Show temperature curve chart -->
    <script type="text/javascript">

      // migrate php data to javascript
      <?php
    	  $temperature_js_array = json_encode($temperature_data_array);
    	  echo "var temperature_data_array = ". $temperature_js_array . ";\n";
    	?>

      // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart', 'line']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawLineColors);

      // Callback that creates and populates a data table, instantiates the pie chart, passes in the data and draws it.
      function drawLineColors() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'X');
        data.addColumn('number', 'Temperatura');

        data.addRows(temperature_data_array);

        var options = {
          hAxis: {
            title: 'Hora',
            showTextEvery: 1,
            textStyle: {
              fontSize: 10
            }
          },
          vAxis: {
            title: 'ºC'
          },
          legend: {
            position: "top"
          },
          colors: ['#FFBF00']
        };

        var chart = new google.visualization.AreaChart(document.getElementById('temperature_chart_div'));
        chart.draw(data, options);
      }
    </script>

    <!-- Show presion curve chart -->
    <script type="text/javascript">

      // migrate php data to javascript
      <?php
    	  $presion_js_array = json_encode($presion_data_array);
    	  echo "var presion_data_array = ". $presion_js_array . ";\n";
    	?>

      // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart', 'line']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawLineColors);

      // Callback that creates and populates a data table, instantiates the pie chart, passes in the data and draws it.
      function drawLineColors() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'X');
        data.addColumn('number', 'Presión');

        data.addRows(presion_data_array);

        var options = {
          hAxis: {
            title: 'Hora',
            showTextEvery: 1,
            textStyle: {
              fontSize: 10
            }
          },
          vAxis: {
            title: 'Atmósferas'
          },
          legend: {
            position: "top"
          },
          colors: ['#00FFED']
        };

        var chart = new google.visualization.AreaChart(document.getElementById('presion_chart_div'));
        chart.draw(data, options);
      }
    </script>

    <!-- Show soil humidity curve chart -->
    <script type="text/javascript">

      // migrate php data to javascript
      <?php
    	  $soil_humidity_js_array = json_encode($soil_humidity_data_array);
    	  echo "var soil_humidity_data_array = ". $soil_humidity_js_array . ";\n";
    	?>

      // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart', 'line']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawLineColors);

      // Callback that creates and populates a data table, instantiates the pie chart, passes in the data and draws it.
      function drawLineColors() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'X');
        data.addColumn('number', 'Humedad del suelo');

        data.addRows(soil_humidity_data_array);

        var options = {
          hAxis: {
            title: 'Hora',
            showTextEvery: 1,
            textStyle: {
              fontSize: 10
            }
          },
          vAxis: {
            title: '% de humedad del suelo'
          },
          legend: {
            position: "top"
          },
          colors: ['#6B4902']
        };

        var chart = new google.visualization.AreaChart(document.getElementById('soil_humidity_chart_div'));
        chart.draw(data, options);
      }
    </script>

    <!-- Show temperature curve chart -->
    <script type="text/javascript">

      // migrate php data to javascript
      <?php
    	  $humidity_js_array = json_encode($humidity_data_array);
    	  echo "var humidity_data_array = ". $humidity_js_array . ";\n";
    	?>

      // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart', 'line']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawLineColors);

      // Callback that creates and populates a data table, instantiates the pie chart, passes in the data and draws it.
      function drawLineColors() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'X');
        data.addColumn('number', 'Humedad ambiente');

        data.addRows(humidity_data_array);

        var options = {
          hAxis: {
            title: 'Hora',
            showTextEvery: 1,
            textStyle: {
              fontSize: 10
            }
          },
          vAxis: {
            title: '% de humedad ambiente'
          },
          legend: {
            position: "top"
          },
          colors: ['#0015FF']
        };

        var chart = new google.visualization.AreaChart(document.getElementById('humidity_chart_div'));
        chart.draw(data, options);
      }
    </script>


  </div> <!-- div class="containe" -->
</body>
</html>
