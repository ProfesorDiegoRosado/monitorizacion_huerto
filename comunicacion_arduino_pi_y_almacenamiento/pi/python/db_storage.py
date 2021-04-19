
import sqlite3
import data
import traceback
import os

class DB:
    date_time_str_format = "YYYY-MM-DD HH:MM:SS.SSS"

    def __init__(self):
        self.db_name = "data"

    def __init__(self, db_name):
        self.db_name = db_name

    def connect(self):
        conn = sqlite3.connect(self.db_name)
        return conn

    def __generate_inserts(self, data: data.Data):
        inserts = []
        date_time = str(data.date_time)
        insert_string = "INSERT INTO <table> (<fields>) VALUES (<values>)"
        insert_string = insert_string.replace("<fields>", "datetime," + "<fields>")
        insert_string = insert_string.replace("<values>", "\"" + date_time + "\"," + "<values>")
        for lectura in data.lecturas:
            fields_string = ""
            values_string = ""
            current_insert = insert_string
            sensor = lectura.sensor
            current_insert = current_insert.replace("<table>", sensor)
            values = lectura.values
            for magnitude,value in values.items():
                fields_string = fields_string + str(magnitude) + ","
                values_string = values_string + str(value) + ","
            fields_string = fields_string.rstrip(",")
            values_string = values_string.rstrip(",")
            current_insert = current_insert.replace("<fields>", fields_string)
            current_insert = current_insert.replace("<values>", values_string)
            inserts.append(current_insert)
        return inserts

    def save(self, data: data.Data):
        conn = self.connect()
        cur = conn.cursor()
        working_dir = os.getcwd()

        inserts = self.__generate_inserts(data)

        try:
            for insert in inserts:
                cur.execute(insert)
                conn.commit()
        except Exception as e:
            traceback.print_exc()

        finally:
            conn.close()
