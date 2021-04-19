import time
import serial
import json
from data import SensorData, Data, load_data
from db_storage import DB
import traceback

db_name = "data.db"

def main():

    db = DB(db_name)
    ser = serial.Serial("/dev/ttyACM0", baudrate=9600, timeout=5) #Modificar el puerto serie de ser necesario

    try:
        while True:
            read = ser.readline()
            print("Leido: " + str(read))
            try:
                data_json = json.loads(read[0:-2])
                print(data_json)
                data = load_data(data_json)
                print("Data object")
                print(data)
                print()
                print("...storing in database...")
                db.save(data)
            except Exception as e:
                print("Error leyendo del puerto serie: " + str(e))
            time.sleep(5*60) # 5 minutos

    except KeyboardInterrupt as e:
        traceback.print_exc()
        print("\nInterrupcion por teclado")
        print(e)
    except ValueError as ve:
        print(ve)
        print("Otra interrupcion")
    finally:
        ser.close()

if __name__ == "__main__":
    main()