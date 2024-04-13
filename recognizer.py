import pytesseract

from preprocessor import preprocess


def recognize(image_path):
    preprocessed_image = preprocess(image_path)
    config = '--psm 6 --oem 3'
    text = pytesseract.image_to_string(preprocessed_image, lang='rus+eng', config=config)
    return text
