import cv2
import numpy as np


class Preprocessor:
    def __init__(self):
        # Загрузка предварительно обученной модели детектора текста EAST
        self.net = cv2.dnn.readNet('assets/frozen_east_text_detection.pb')

    def enhance_contrast(self, image):
        alpha = 1.5  # Коэффициент контрастности
        beta = 0  # Яркость
        return cv2.convertScaleAbs(image, alpha=alpha, beta=beta)

    def preprocess(self, image):
        # Копирование исходного изображения для последующего использования
        orig = image.copy()

        image = self.enhance_contrast(image)

        (H, W) = image.shape[:2]
        new_size = (320, 320)
        rW, rH = W / float(new_size[0]), H / float(new_size[1])

        resized_image = cv2.resize(image, (new_size[0], int(H * (new_size[0] / W))))

        blob = cv2.dnn.blobFromImage(resized_image, 1.0, new_size,
                                     (123.68, 116.78, 103.94), swapRB=True, crop=False)
        self.net.setInput(blob)
        scores, geometry = self.net.forward(["feature_fusion/Conv_7/Sigmoid", "feature_fusion/concat_3"])
        try:
            rects, confidences = self.decode_predictions(scores, geometry)
            boxes = self.non_max_suppression(np.array(rects), confidences)
            ROIs = [self.extract_ROI(orig, box, rW, rH) for box in boxes]
            orig_with_boxes = self.draw_boxes(orig, boxes, rW, rH)
            return orig_with_boxes, ROIs
        except Exception as e:
            print(f"An error occurred during preprocessing: {e}")
            return orig, []

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