import cv2
import torch
from pytesseract import pytesseract
from transformers import BertTokenizer, BertForSequenceClassification

from preprocessor import Preprocessor


# Класс Recognizer предназначен для распознавания и классификации текста из изображений.
class Recognizer:
    """
    Инициализация модели BERT и токенизатора для последующей классификации текста.

    Аттрибуты:
        model (BertForSequenceClassification): Модель для классификации текста.
        tokenizer (BertTokenizer): Токенизатор для преобразования текста в формат, подходящий для модели.
        preprocessor (Preprocessor): Экземпляр класса Preprocessor для предварительной обработки изображений.
    """

    def __init__(self, model_path='bert-base-multilingual-cased'):
        # Загрузка предобученной модели BERT и соответствующего токенизатора.
        self.model = BertForSequenceClassification.from_pretrained(model_path)
        self.tokenizer = BertTokenizer.from_pretrained(model_path)
        # Создание экземпляра класса Preprocessor для предобработки изображений.
        self.preprocessor = Preprocessor()

    # Метод image_to_string преобразует изображение в строку текста с использованием OCR (Optical Character Recognition).
    def image_to_string(self, image):
        # Конфигурация для pytesseract для оптимизации распознавания текста.
        config = '--psm 7 --oem 1'
        # Преобразование изображения в строку текста с поддержкой русского и английского языков.
        return pytesseract.image_to_string(image, lang='rus+eng', config=config)

    # Метод classify_text классифицирует текст с помощью модели BERT.
    def classify_text(self, text):
        # Преобразование текста в формат, подходящий для модели, с помощью токенизатора.
        inputs = self.tokenizer(text, return_tensors='pt')
        # Получение результатов классификации от модели BERT.
        outputs = self.model(**inputs)
        # Применение функции softmax для получения вероятностей классов.
        probs = torch.nn.functional.softmax(outputs.logits, dim=-1)
        # Возвращение исходного текста и метки класса с наибольшей вероятностью.
        return text, torch.argmax(probs).item()

    # Основной метод recognize выполняет распознавание текста на изображении и его классификацию.
    def recognize(self, image_path):
        # Чтение изображения по указанному пути.
        image = cv2.imread(image_path)
        # Предварительная обработка изображения с помощью экземпляра класса Preprocessor.
        orig_with_boxes, ROIs = self.preprocessor.preprocess(image)
        results = []

        # Проверка наличия областей интереса (ROIs) после предобработки изображения.
        if not ROIs:
            print("Список ROIs пуст.")
            return orig_with_boxes, []

        # Обработка каждой области интереса (ROI), распознавание и классификация текста.
        for roi in ROIs:
            text = self.image_to_string(roi)
            _, class_label = self.classify_text(text)
            results.append((text, class_label))

        # Возвращение обработанного изображения и результатов распознавания и классификации.
        return orig_with_boxes, results


recognizer = Recognizer()
orig_with_boxes, results = recognizer.recognize(
    'D:\\DevDrive\\localzet-dev\\hacks-ai-dpr\\assets\\subm_example\\IMG_20240329_095208778~2.jpg')

for i, (text, class_label) in enumerate(results):
    print(f'ROI {i}: Class {class_label} - {text}')

cv2.imshow('Text Detection', orig_with_boxes)
cv2.waitKey(0)
cv2.destroyAllWindows()
