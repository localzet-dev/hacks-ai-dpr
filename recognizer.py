import cv2
import numpy as np
import torch
from easyocr import Reader
from transformers import BertTokenizer, BertForSequenceClassification


# Класс Recognizer предназначен для распознавания и классификации текста из изображений.
class Recognizer:
    def __init__(self, model_path='bert-base-multilingual-cased'):
        self.net = cv2.dnn.readNet('assets/frozen_east_text_detection.pb')
        self.model = BertForSequenceClassification.from_pretrained(model_path)
        self.tokenizer = BertTokenizer.from_pretrained(model_path)
        self.reader = Reader(['ru'])
        self.model.eval()

    def image_to_string(self, image):
        results = self.reader.readtext(image, detail=0)
        return ' '.join(results)

    def classify_text(self, text):
        with torch.no_grad():
            inputs = self.tokenizer(text, return_tensors='pt', truncation=True, max_length=512)
            outputs = self.model(**inputs)
            probs = torch.nn.functional.softmax(outputs.logits, dim=-1)
            return text, torch.argmax(probs).item()

    def recognize(self, image_path):
        image = cv2.imread(image_path)
        orig_with_boxes, ROIs = self.preprocess(image)
        results = []

        if not ROIs:
            print("No ROIs found.")
            return orig_with_boxes, []

        for roi in ROIs:
            text = self.image_to_string(roi)
            _, class_label = self.classify_text(text)
            results.append((text, class_label))

        return orig_with_boxes, results

    def enhance_contrast(self, image):
        alpha = 1.5
        beta = 0
        return cv2.convertScaleAbs(image, alpha=alpha, beta=beta)

    def preprocess(self, image):
        orig = image.copy()
        image = self.enhance_contrast(image)

        (H, W) = image.shape[:2]
        rW, rH = W / float(320), H / float(320)

        resized_image = cv2.resize(image, (320, int(H * (320 / W))))

        blob = cv2.dnn.blobFromImage(resized_image, 1.0, (320, 320),
                                     (123.68, 116.78, 103.94), swapRB=True, crop=False)
        self.net.setInput(blob)
        scores, geometry = self.net.forward(["feature_fusion/Conv_7/Sigmoid", "feature_fusion/concat_3"])

        rects, confidences = self.decode_predictions(scores, geometry)
        boxes = self.non_max_suppression(np.array(rects), confidences)

        ROIs = [self.extract_ROI(orig, box, rW, rH) for box in boxes]

        orig_with_boxes = self.draw_boxes(orig, boxes, rW, rH)

        return orig_with_boxes, ROIs

    def extract_ROI(self, orig_image, box_coords, rW, rH):
        # Расчет координат начала и конца области интереса (ROI)
        startX, startY, endX, endY = box_coords
        startX = int(startX * rW)
        startY = int(startY * rH)
        endX = int(endX * rW)
        endY = int(endY * rH)

        # Извлечение и возврат ROI из исходного изображения
        return orig_image[startY:endY, startX:endX]

    def draw_boxes(self, orig_image, boxes_coords, rW, rH):
        # Рисование прямоугольников вокруг обнаруженных текстовых областей на исходном изображении
        for (startX, startY, endX, endY) in boxes_coords:
            startX = int(startX * rW)
            startY = int(startY * rH)
            endX = int(endX * rW)
            endY = int(endY * rH)

            cv2.rectangle(orig_image,
                          (startX, startY),
                          (endX, endY),
                          (0, 255, 0), 2)

        # Возврат исходного изображения с нарисованными прямоугольниками
        return orig_image

    def decode_predictions(self, scores, geometry, min_confidence=0.5):
        # Инициализация списка прямоугольников и уверенностей
        (numRows, numCols) = scores.shape[2:4]
        rects = []
        confidences = []

        # Цикл по строкам выходных данных сети
        for y in range(0, numRows):
            scoresData = scores[0, 0, y]
            xData0 = geometry[0, 0, y]
            xData1 = geometry[0, 1, y]
            xData2 = geometry[0, 2, y]
            xData3 = geometry[0, 3, y]
            anglesData = geometry[0, 4, y]

            # Цикл по столбцам выходных данных сети
            for x in range(0, numCols):
                # Пропуск низких уверенностей
                if scoresData[x] < min_confidence:
                    continue

                # Расчет смещения и угла для каждого прямоугольника
                (offsetX, offsetY) = (x * 4.0, y * 4.0)
                angle = anglesData[x]
                cos = np.cos(angle)
                sin = np.sin(angle)

                # Расчет размеров и координат прямоугольника
                h = xData0[x] + xData2[x]
                w = xData1[x] + xData3[x]

                # Расчет координат конца прямоугольника
                endX = int(offsetX + (cos * xData1[x]) + (sin * xData2[x]))
                endY = int(offsetY - (sin * xData1[x]) + (cos * xData2[x]))
                startX = int(endX - w)
                startY = int(endY - h)

                # Добавление прямоугольника и уверенности в списки
                rects.append((startX, startY, endX, endY))
                confidences.append(scoresData[x])

        # Возврат списка прямоугольников и уверенностей за пределами циклов
        return rects, confidences

    def non_max_suppression(self, boxes, probs=None, overlapThresh=0.3):
        if len(boxes) == 0:
            return []
        if boxes.dtype.kind == "i":
            boxes = boxes.astype("float")
        pick = []
        x1 = boxes[:, 0]
        y1 = boxes[:, 1]
        x2 = boxes[:, 2]
        y2 = boxes[:, 3]
        area = (x2 - x1 + 1) * (y2 - y1 + 1)
        idxs = np.argsort(probs)
        while len(idxs) > 0:
            last = len(idxs) - 1
            i = idxs[last]
            pick.append(i)
            xx1 = np.maximum(x1[i], x1[idxs[:last]])
            yy1 = np.maximum(y1[i], y1[idxs[:last]])
            xx2 = np.minimum(x2[i], x2[idxs[:last]])
            yy2 = np.minimum(y2[i], y2[idxs[:last]])
            w = np.maximum(0, xx2 - xx1 + 1)
            h = np.maximum(0, yy2 - yy1 + 1)
            overlap = (w * h) / area[idxs[:last]]
            idxs = np.delete(idxs, np.concatenate(([last], np.where(overlap > overlapThresh)[0])))
        return boxes[pick].astype("int")


recognizer = Recognizer()

orig_with_boxes, results = recognizer.recognize(
    'D:\\DevDrive\\localzet-dev\\hacks-ai-dpr\\assets\\subm_example\\IMG_20240329_095208778~2.jpg')

for i, (text, class_label) in enumerate(results):
    print(f'ROI {i}: Class {class_label} - {text}')