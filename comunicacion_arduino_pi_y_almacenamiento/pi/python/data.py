
# This is an example a json read
"""{
   "lectura":[
      {
         "sensor":"DHT11",
         "valores":{
            "humedad":61.0,
            "temperatura":26.0
         }
      },
      {
         "sensor":"DZ0325",
         "valores":{
            "humedad_terreno":1
         }
      },
      {
         "sensor":"BMP180",
         "valores":{
            "temperatura":24,
            "presion":1000
         }
      }
   ]
}
"""

# This is an open structure that can deal with adding new sensors and values
# we just add the timestamp to json data

from datetime import datetime

class SensorData:

    def __init__(self, sensor_name):
        self.sensor = sensor_name
        self.values = {}

    def add_value(self,magnitude, value):
        self.values[magnitude] = value

    def __repr__(self): # be unambiguous
        s = "Sensor:" + self.sensor + ";"
        s = s + "Values:["
        for k,v in self.values.items():
            s = s + str(k) + ":" + str(v) + ","
        if s[-1]==",": # remove last , if exists
            s = s[:-1]
        s = s + "]"
        return s

    def __str__(self): # be readable
        return self.__repr__()


class Data:
    date_time_str_format = "YYYY-MM-DD HH:MM:SS.SSS"

    def __init__(self):
        self.lecturas = []
        self.date_time = datetime.now()

    def add(self, lectura):
        self.lecturas.append(lectura)

    def __repr__(self):  # be unambiguous
        s = "datetime:" + str(self.date_time) + ";"
        s = s + "lecturas: ["
        for d in self.lecturas:
            s = s + str(d) + ","
        if s[-1]==",": # remove last , if exists
            s = s[:-1]
        s = s + "]"
        return s

    def __str__(self):  # be readable
        return self.__repr__()

def load_data(json):
    """{
       "lectura":[
          {
             "sensor":"DHT11",
             "valores":{
                "humedad":61.0,
                "temperatura":26.0
             }
          },
          {
             "sensor":"DZ0325",
             "valores":{
                "humedad_terreno":1
             }
          },
          {
             "sensor":"BMP180",
             "valores":{
                "temperatura":24,
                "presion":1000
             }
          }
       ]
    }
    """
    data = Data()
    lecturas = json["lectura"]
    for l in lecturas:
        sensor_name = l["sensor"]
        sensorData = SensorData(sensor_name)
        values = l["valores"]
        for k,v in values.items():
            magnitude = k
            value = v
            sensorData.add_value(magnitude, value)
        data.add(sensorData)
    return data
