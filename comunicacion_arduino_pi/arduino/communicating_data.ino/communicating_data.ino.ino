//Include libraries
// BMP180
#include <SFE_BMP180.h>
#include <Wire.h>
// DHT11
#include <DHT.h>

// You will need to create an SFE_BMP180 object, here called "pressure":
SFE_BMP180 pressure;

// Constatst
#define DELAY_TIME 5000
#define ALTITUDE 300.0 // Altitude of SparkFun's HQ in Boulder, CO. in meters
// Definimos el pin digital donde se conecta el sensor
#define DHTPIN 2
#define DHTTYPE DHT11
// Hygrometer
//Constants 
const int hygrometer = A0;  //Hygrometer sensor analog pin output at pin A0 of Arduino


// Inicializamos el sensor DHT11
DHT dht(DHTPIN, DHTTYPE);


void start_BMP180() {
  // Initialize the sensor (it is important to get calibration values stored on the device).
  if (pressure.begin()) {
    //Serial.println("BMP180 init success");
  } else {
    // Oops, something went wrong, this is usually a connection problem,
    // see the comments at the top of this sketch for the proper connections.
    Serial.println("BMP180 init fail\n\n");
    while(1); // Pause forever.
  }
  
}

void read_DHT11() {
 
  // Leemos la humedad relativa
  float h = dht.readHumidity();
  // Leemos la temperatura en grados centígrados (por defecto)
  float t = dht.readTemperature();
 
  // Comprobamos si ha habido algún error en la lectura
  if (isnan(h) || isnan(t)) {
    Serial.println("Error obteniendo los datos del sensor DHT11");
    return;
  }
 
  Serial.print("{\"sensor\":\"DHT11\",\"valores\":{");
  Serial.print("\"humedad\":");
  Serial.print(h);
  Serial.print(",\"temperatura\":");
  Serial.print(t);
  Serial.print("}}");
}

void read_hygrometer() {
  int value;
  value = analogRead(hygrometer);   //Read analog value 
  value = constrain(value,400,1023);  //Keep the ranges!
  value = map(value,400,1023,100,0);  //Map value : 400 will be 100 and 1023 will be 0

  Serial.print("{\"sensor\":\"DZ0325\",\"valores\":{");
  Serial.print("\"humedad_terreno\":");
  Serial.print(value);
  Serial.print("}}");
}

void read_BMP180() {
  char status;
  double T,P,p0,a;

  // You must first get a temperature measurement to perform a pressure reading.
  status = pressure.startTemperature();
  if (status != 0) {
    // Wait for the measurement to complete:
    delay(status);

    // Retrieve the completed temperature measurement: Note that the measurement is stored in the variable T.
    // Function returns 1 if successful, 0 if failure.

    status = pressure.getTemperature(T);
    if (status != 0) {
      // Start a pressure measurement:
      // The parameter is the oversampling setting, from 0 to 3 (highest res, longest wait).

      status = pressure.startPressure(3);
      if (status != 0)
      {
        // Wait for the measurement to complete:
        delay(status);

        // Retrieve the completed pressure measurement:
        // Note that the measurement is stored in the variable P.
        // Note also that the function requires the previous temperature measurement (T).
        // (If temperature is stable, you can do one temperature measurement for a number of pressure measurements.)
        // Function returns 1 if successful, 0 if failure.

        status = pressure.getPressure(P,T);
        if (status != 0)
        {
          Serial.print("{\"sensor\":\"BMP180\",\"valores\":{");
          Serial.print("\"temperatura\":");
          Serial.print((int)T);
          Serial.print(",\"presion\":");
          Serial.print((int)P);
          Serial.print("}}");

        } else {
          Serial.println("error retrieving pressure measurement\n");
        }
      } else {
        Serial.println("error starting pressure measurement\n");
      }
    } else {
      Serial.println("error retrieving temperature measurement\n");
    }
  } else {
    Serial.println("error starting temperature measurement\n");
  }
}

void setup()
{
  Serial.begin(9600);
  start_BMP180();
  // Comenzamos el sensor DHT
  dht.begin();
}

void loop()
{

  Serial.print("{\"lectura\":[");
  read_DHT11();

  Serial.print(",");
  read_hygrometer();

  Serial.print(",");
  read_BMP180();
  Serial.println("]}");

  delay(DELAY_TIME);  // Pause for DELAY_TIME milliseconds.
}
