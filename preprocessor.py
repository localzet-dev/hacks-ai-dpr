import cv2
import numpy as np


class Preprocessor:
    def __init__(self):
        # Загрузка предварительно обученной модели детектора текста EAST
        self.net = cv2.dnn.readNet('assets/frozen_east_text_detection.pb')

    def preprocess(self, image):
        # Копирование исходного изображения для последующего использования
        orig = image.copy()
        # Получение размеров изображения
        (H, W) = image.shape[:2]
        # Определение нового размера для изменения размера изображения
        new_size = (320, 320)
        # Расчет соотношений для масштабирования ограничивающих рамок обратно к размеру исходного изображения
        rW, rH = W / new_size[0], H / new_size[1]
        # Изменение размера изображения до нового размера
        resized_image = cv2.resize(image, new_size)
        # Создание блоба из измененного изображения для ввода в нейронную сеть
        blob = cv2.dnn.blobFromImage(resized_image, 1.0, new_size,
                                     (123.68, 116.78, 103.94), swapRB=True, crop=False)
        # Установка блоба в качестве входных данных сети
        self.net.setInput(blob)
        # Прямой проход через сеть для получения оценок и геометрии
        scores, geometry = self.net.forward(["feature_fusion/Conv_7/Sigmoid", "feature_fusion/concat_3"])
        # Декодирование предсказаний для получения координат ограничивающих рамок и уверенности
        rects, confidences = self.decode_predictions(scores, geometry)
        # Применение подавления немаксимальных значений для фильтрации перекрывающихся рамок
        boxes = self.non_max_suppression(np.array(rects), confidences)

        # Извлечение регионов интереса на основе ограничивающих рамок
        ROIs = [self.extract_ROI(orig, box, rW, rH) for box in boxes]

        # Рисование ограничивающих рамок на исходном изображении
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

                # Возврат списка прямоугольников и уверенностей
            return rects, confidences

    def non_max_suppression(self, boxes, probs, overlapThresh=0.3):
        # Применение подавления немаксимальных значений для фильтрации перекрывающихся рамок
        return cv2.dnn.NMSBoxes(boxes, probs, overlapThresh)
