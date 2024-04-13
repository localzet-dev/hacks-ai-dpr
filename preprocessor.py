import cv2
import numpy as np
from pytesseract import pytesseract

# Load the EAST model for text detection
net = cv2.dnn.readNet('assets/frozen_east_text_detection.pb')


def preprocess(image_path):
    # Read the image
    image = cv2.imread(image_path)
    orig = image.copy()
    (H, W) = image.shape[:2]

    # Set the new width and height and then determine the ratio in change
    # for both the width and height
    (newW, newH) = (320, 320)
    rW = W / float(newW)
    rH = H / float(newH)

    # Resize the image and grab the new image dimensions
    image = cv2.resize(image, (newW, newH))
    (H, W) = image.shape[:2]

    # Define the output layer names for the EAST detector model
    layerNames = [
        "feature_fusion/Conv_7/Sigmoid",
        "feature_fusion/concat_3"]

    # Construct a blob from the image and then perform a forward pass of
    # the model to obtain the two output layer sets
    blob = cv2.dnn.blobFromImage(image, 1.0, (W, H),
                                 (123.68, 116.78, 103.94), swapRB=True, crop=False)
    net.setInput(blob)
    (scores, geometry) = net.forward(layerNames)

    # Decode predictions and apply non-maxima suppression
    (rects, confidences) = decode_predictions(scores, geometry)
    boxes = non_max_suppression(np.array(rects), probs=confidences)

    ROIs = []

    for (startX, startY, endX, endY) in boxes:
        # Scale the bounding box coordinates based on the respective ratios
        startX = int(startX * rW)
        startY = int(startY * rH)
        endX = int(endX * rW)
        endY = int(endY * rH)

        # Extract the region of interest
        ROI = orig[startY:endY, startX:endX]

        # You can now use this ROI for OCR with Tesseract or another OCR engine

        ROIs.append(ROI)

    # return ROIs
    for (startX, startY, endX, endY) in boxes:
        cv2.rectangle(orig, (startX, startY), (endX, endY), (0, 255, 0), 2)

    return orig, ROIs

def decode_predictions(scores, geometry, min_confidence=0.5):
    # инициализировать список ограничивающих рамок и соответствующие уверенности
    (numRows, numCols) = scores.shape[2:4]
    rects = []
    confidences = []

    # цикл по строкам
    for y in range(0, numRows):
        # извлечь оценки (вероятности) и геометрические данные, используемые для вычисления координат ограничивающих рамок
        scoresData = scores[0, 0, y]
        xData0 = geometry[0, 0, y]
        xData1 = geometry[0, 1, y]
        xData2 = geometry[0, 2, y]
        xData3 = geometry[0, 3, y]
        anglesData = geometry[0, 4, y]

        # цикл по колонкам
        for x in range(0, numCols):
            # если вероятность меньше минимальной уверенности, игнорировать
            if scoresData[x] < min_confidence:
                continue

            # вычислить смещение на основе размера функции (4x)
            (offsetX, offsetY) = (x * 4.0, y * 4.0)

            # извлечь угол наклона и вычислить синус и косинус
            angle = anglesData[x]
            cos = np.cos(angle)
            sin = np.sin(angle)

            # использовать геометрию для вычисления ширины и высоты ограничивающей рамки
            h = xData0[x] + xData2[x]
            w = xData1[x] + xData3[x]

            # вычислить начальные и конечные координаты ограничивающей рамки
            endX = int(offsetX + (cos * xData1[x]) + (sin * xData2[x]))
            endY = int(offsetY - (sin * xData1[x]) + (cos * xData2[x]))
            startX = int(endX - w)
            startY = int(endY - h)

            # добавить ограничивающую рамку и уверенность в соответствующие списки
            rects.append((startX, startY, endX, endY))
            confidences.append(scoresData[x])

    # вернуть список ограничивающих рамок и уверенностей
    return (rects, confidences)


def non_max_suppression(boxes, probs=None, overlapThresh=0.3):
    # если нет ограничивающих рамок, вернуть пустой список
    if len(boxes) == 0:
        return []

    # если ограничивающие рамки целочисленные, преобразовать их в float
    if boxes.dtype.kind == "i":
        boxes = boxes.astype("float")

    # инициализировать список индексов для сохранения
    pick = []

    # получить координаты ограничивающих рамок
    x1 = boxes[:, 0]
    y1 = boxes[:, 1]
    x2 = boxes[:, 2]
    y2 = boxes[:, 3]

    # вычислить площадь ограничивающих рамок и сортировать их по убыванию вероятности
    area = (x2 - x1 + 1) * (y2 - y1 + 1)
    idxs = np.argsort(probs)

    # пока ещё есть индексы для обработки
    while len(idxs) > 0:
        # взять последний индекс в списке индексов и добавить его в список выбора
        last = len(idxs) - 1
        i = idxs[last]
        pick.append(i)

        # найти наибольшее (x, y) координаты для начала рамки и наименьшее (x, y) координаты для конца рамки
        xx1 = np.maximum(x1[i], x1[idxs[:last]])
        yy1 = np.maximum(y1[i], y1[idxs[:last]])
        xx2 = np.minimum(x2[i], x2[idxs[:last]])
        yy2 = np.minimum(y2[i], y2[idxs[:last]])

        # вычислить ширину и высоту перекрытия
        w = np.maximum(0, xx2 - xx1 + 1)
        h = np.maximum(0, yy2 - yy1 + 1)

        # вычислить отношение перекрытия к площади ограничивающей рамки (IoU)
        overlap = (w * h) / area[idxs[:last]]

        # удалить все индексы из списка, которые имеют IoU больше чем заданный порог
        idxs = np.delete(idxs, np.concatenate(([last],
                                               np.where(overlap > overlapThresh)[0])))

    # вернуть только ограничивающие рамки, которые были выбраны
    return boxes[pick].astype("int")


# Используйте эту функцию с путем к вашему изображению
original_image, ROIs = preprocess('D:\\DevDrive\\localzet-dev\\hacks-ai-dpr\\assets\\subm_example\\IMG_20240329_095208778~2.jpg')

cv2.imshow('Text Detection', original_image)

# Extract and print text from each ROI
for i, ROI in enumerate(ROIs):
    text = pytesseract.image_to_string(ROI)
    print(f'ROI {i}:', text)

cv2.waitKey(0)
cv2.destroyAllWindows()