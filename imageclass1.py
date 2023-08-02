from PIL import Image, ImageDraw
import pytesseract
import os

class ImageClass:

  def __init__(self, file_path):
    self.file_path = file_path

  def process(self):
    
    print(f"Processing image: {self.file_path}")

    processed_path = self.get_processed_path()

    try:
      image = Image.open(self.file_path)

      text = pytesseract.image_to_string(image)
      text = self.remove_contact_info(text)

      draw = ImageDraw.Draw(image)
      draw.text((10, 10), text)

      image.save(processed_path)

      print(f"Processed image saved to: {processed_path}")
      return processed_path

    except IOError as e:
      print(f"Error processing image: {e}")
      return None

  def remove_contact_info(self, text):
    text = re.sub(r'\d{3}[-.\s]?\d{3}[-.\s]?\d{4}', '', text, count=1)
    text = re.sub(r'\S+@\S+', '', text, count=1)
    return text

  def get_processed_path(self):
    filename = os.path.basename(self.file_path)
    return f"{os.path.splitext(filename)[0]}_processed.{os.path.splitext(filename)[1]}"