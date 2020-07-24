from PIL import Image
import pytesseract
pytesseract.pytesseract.tesseract_cmd = r"C:\Program Files\Tesseract-OCR\tesseract.exe"
im = Image.open('3.jpg')

for i in range(1):
    text = pytesseract.image_to_string(im)
    print(text)