import mysql.connector
from PDFClass import PDFClass
from WordClass import WordClass 
from ImageClass import ImageClass

class MainClass:

  def __init__(self):
    self.db = mysql.connector.connect(
      host='localhost',
      user='user',
      password='password',  
      database='files_db'
    )
    self.cursor = self.db.cursor()

  def get_files(self):
    sql = "SELECT * FROM files"
    self.cursor.execute(sql)
    return self.cursor.fetchall()

  def update_file(self, file_path):
    sql = "UPDATE files SET processed_path = %s WHERE file_path = %s"
    values = (file_path, file_path)
    self.cursor.execute(sql, values)
    self.db.commit()  

  def process_files(self):
    files = self.get_files()
    
    for file in files:
      file_path = file[0]
      file_type = file[1]

      print(f"Processing file: {file_path}")

      if file_type == "pdf":
        processor = PDFClass(file_path, first_only=True)
      elif file_type == "word":
        processor = WordClass(file_path, first_only=True)
      elif file_type == "image":
        processor = ImageClass(file_path)

      processed_path = processor.process()  
      self.update_file(processed_path)

if __name__ == '__main__':
  main = MainClass()
  main.process_files()