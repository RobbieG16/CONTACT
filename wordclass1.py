import re
from docx import Document

class WordClass:

  def __init__(self, file_path, first_only=False): 
    self.file_path = file_path
    self.first_only = first_only

  def process(self):

    print(f"Processing Word document: {self.file_path}")
    
    processed_path = f"{self.file_path.replace('.docx', '')}_processed.docx"

    try:
      with open(self.file_path, 'rb') as doc_file:
        doc = Document(doc_file)

        for para in doc.paragraphs:
          text = para.text
          
          if self.first_only:
            text = re.sub(r'\d{3}[-.\s]?\d{3}[-.\s]?\d{4}', '', text, count=1)
            text = re.sub(r'\S+@\S+', '', text, count=1)
          else:  
            text = re.sub(r'\d{3}[-.\s]?\d{3}[-.\s]?\d{4}', '', text)
            text = re.sub(r'\S+@\S+', '', text)
            
          para.text = text

        with open(processed_path, 'wb') as out_file:
          doc.save(out_file)
      
      print(f"Processed Word document saved to: {processed_path}")
      return processed_path

    except IOError as e:
      print(f"Error processing Word document: {e}")
      return None