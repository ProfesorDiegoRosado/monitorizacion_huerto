<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 01234567890123456789012345   - Example datetime for extracting data
// 2021-04-14 09:20:45.313552
function get_datetime($row) {
  return $row["datetime"];
}

function get_date($row) {
  return intval(substr(get_datetime($row), 8,2));
}

function get_hour($row) {
  return intval(substr(get_datetime($row), 11,2));
}

function get_field($row, $field) {
  return intval($row[$field]);
}

// Read array from last day of available data en db with $table and $column, 1 value per hour
function read_magnitude($table, $column) {
  $sensor_readings = array(
    0 => 0.0,
    1 => 0.0,
    2 => 0.0,
    3 => 0.0,
    4 => 0.0,
    5 => 0.0,
    6 => 0.0,
    7 => 0.0,
    8 => 0.0,
    9 => 0.0,
    10 => 0.0,
    11 => 0.0,
    12 => 0.0,
    13 => 0.0,
    14 => 0.0,
    15 => 0.0,
    16 => 0.0,
    17 => 0.0,
    18 => 0.0,
    19 => 0.0,
    20 => 0.0,
    21 => 0.0,
    22 => 0.0,
    23 => 0.0
  );

  $db = new SQLite3('../comunicacion_arduino_pi_y_almacenamiento/pi/python/data.db');

  $results = $db->query('SELECT * FROM ' . $table . ' ORDER BY datetime DESC');

  $row = $results->fetchArray();
  $current_hour = get_hour($row);
  $current_date = get_date($row);
  $end = False;

  $sensor_readings[$current_hour] = get_field($row, $column);

  // Populate array with one value each hour of last day of data
  while(!$end) {
    //print_r($row); echo("<br/>");
    $date = get_date($row);
    $hour = get_hour($row);

    // Break if we change to another day
    if ($current_date!=$date) {
      break;
    }

    // Update data only if new hour
    if ($current_hour!=$hour) {
      $temp = get_field($row, $column);
      //print("Temperatura: " . $temp . "<br/>");
      $sensor_readings[$hour] = $temp;
      $current_hour = $hour;
    }

    $row = $results->fetchArray();
    $end = !$row;  # if fetchArray() empty then return False
  }
  //print("Sensor readings: <br/>");
  //print_r($sensor_readings);

  return $sensor_readings;

}

//print_r(read_magnitude("BMP180","temperatura"));

?>
