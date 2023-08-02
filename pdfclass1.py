import re 
import PyPDF2

class PDFClass:

  def __init__(self, file_path, first_only=False):
    self.file_path = file_path
    self.first_only = first_only

  def process(self):

    print(f"Processing PDF: {self.file_path}") 

    processed_path = f"{self.file_path.replace('.pdf', '')}_processed.pdf"

    try:
      with open(self.file_path, 'rb') as pdf_file:
        pdf_reader = PyPDF2.PdfFileReader(pdf_file)
        pdf_writer = PyPDF2.PdfFileWriter()

        for page in pdf_reader.pages:
          page_content = page.getContents()
          
          if self.first_only:
            page_content = re.sub(r'\d{3}[-.\s]?\d{3}[-.\s]?\d{4}', '', page_content, count=1)
            page_content = re.sub(r'\S+@\S+', '', page_content, count=1)
          else:
            page_content = re.sub(r'\d{3}[-.\s]?\d{3}[-.\s]?\d{4}', '', page_content)
            page_content = re.sub(r'\S+@\S+', '', page_content)
            
          new_page = pdf_writer.addBlankPage(page.mediaBox)
          new_page.mergePage(page_content)

        with open(processed_path, 'wb') as out_file:
          pdf_writer.write(out_file)
      
      print(f"Processed PDF saved to: {processed_path}")
      return processed_path

    except IOError as e:
      print(f"Error processing PDF: {e}")
      return None