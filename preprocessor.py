import cv2


# from PIL import Image


def preprocess(image_path):
    # img = Image.open(image_path)
    image = cv2.imread(image_path)
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
    return gray
