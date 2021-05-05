import time
import serial
import json

ser = serial.Serial("/dev/ttyACM0", baudrate=9600) #Modificar el puerto serie de ser necesario

try:
    while True:
        read = ser.readline()
        print("Leido: " + str(read))
        data = json.loads(read[0:-2])
        print(data)

except KeyboardInterrupt:
    print("\nInterrupcion por teclado")
except ValueError as ve:
    print(ve)
    print("Otra interrupcion")
finally:
    ser.close()
