import re

import cv2
from PIL import Image
from pytesseract import pytesseract
from ultralytics import YOLO


class Preprocessor:
    def preprocess(self, image_path):
        # Load the image using PIL
        img = Image.open(image_path)
        return img


class YolovDetector:
    def __init__(self):
        # Загрузите предварительно обученную модель YOLOv8
        self.model = YOLO("assets/yolov8n.pt")
        self.model.train(data='coco128.yaml', epochs=3)
        # self.model.val()

    def detect(self, image):
        results = self.model(source=image)
        return results

    def get_product_name(self, results):
        # Check if results is empty
        if not results:
            return None  # or handle the empty case as needed

        # Assuming each element in results is a list/tuple with the last element being the class label
        labels = []
        for det in results:
            if isinstance(det, (list, tuple)) and len(det) > 5:
                labels.append(det[-1])
            else:
                # Handle cases where det does not have the expected structure
                pass

        names = [self.model.names[int(label)] for label in labels]
        return names[0] if names else None

    def extract_price(self, text):
        # Извлеките цену из текста с помощью регулярного выражения
        match = re.search(r'\d+,\d{2}', text)
        return match.group() if match else None


def visualize_results(image, results, detector):
    # Draw bounding boxes and labels on the image
    for det in results:
        if isinstance(det, (list, tuple)) and len(det) == 6:
            x1, y1, x2, y2, conf, cls = map(int, det[:6])
            name = detector.model.names[cls]
            cv2.rectangle(image, (x1, y1), (x2, y2), (255, 0, 0), 2)
            cv2.putText(image, name, (x1, y1 - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.9, (36, 255, 12), 2)

    # Display the image
    cv2.imshow('Detected Objects', image)
    cv2.waitKey(0)
    cv2.destroyAllWindows()

# Create instances of Preprocessor and YolovDetector
preprocessor = Preprocessor()
detector = YolovDetector()

# Путь к вашему изображению
image_path = 'D:\\DevDrive\\localzet-dev\\hacks-ai-dpr\\assets\\subm_example\\IMG_20240329_095208778~2.jpg'

# Preprocess the image
preprocessed_image = preprocessor.preprocess(image_path)

# Detect objects in the image
results = detector.detect(preprocessed_image)

# Visualize the results
# Uncomment the following lines if you want to visualize the results
image = cv2.imread(image_path)
visualize_results(image, results, detector)

# Get product name from predictions
product_name = detector.get_product_name(results)

# Uncomment the following lines if you want to extract price from text using pytesseract
text = pytesseract.image_to_string(preprocessed_image, lang='rus')
price = detector.extract_price(text)

print(f'Product Name: {product_name}')
