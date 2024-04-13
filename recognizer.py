import cv2
import torch
from pytesseract import pytesseract
from transformers import BertTokenizer, BertForSequenceClassification

from preprocessor import Preprocessor


class Recognizer:
    def __init__(self, model_path='bert-base-uncased'):
        self.model = BertForSequenceClassification.from_pretrained(model_path)
        self.tokenizer = BertTokenizer.from_pretrained(model_path)
        self.preprocessor = Preprocessor()

    def image_to_string(self, image):
        config = '--psm 6 --oem 3'
        return pytesseract.image_to_string(image, lang='rus+eng', config=config)

    def classify_text(self, text):
        inputs = self.tokenizer(text, return_tensors='pt')
        outputs = self.model(**inputs)
        probs = torch.nn.functional.softmax(outputs.logits, dim=-1)
        return text, torch.argmax(probs).item()

    def recognize(self, image_path):
        image = cv2.imread(image_path)

        orig_with_boxes, ROIs = self.preprocessor.preprocess(image)
        results = []
        for roi in ROIs:
            roi_image = roi[0]
            text, class_label = self.classify_text(roi_image)
            results.append((text, class_label))
        return orig_with_boxes, results


recognizer = Recognizer()
orig_with_boxes, results = recognizer.recognize(
    'D:\\DevDrive\\localzet-dev\\hacks-ai-dpr\\assets\\subm_example\\IMG_20240329_095208778~2.jpg')

for i, (_, class_label) in enumerate(results[1]):
    print(f'ROI {i}: Class {class_label[0]} - {class_label[1]}')

cv2.imshow('Text Detection', orig_with_boxes)
cv2.waitKey(0)
cv2.destroyAllWindows()
