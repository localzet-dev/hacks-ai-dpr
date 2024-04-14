# import cv2
#
# from recognizer import Recognizer
#
# recognizer = Recognizer()
#
# orig_with_boxes, results = recognizer.recognize(
#     'D:\\DevDrive\\localzet-dev\\hacks-ai-dpr\\assets\\subm_example\\IMG_20240329_095208778~2.jpg')
#
# for i, (text, class_label) in enumerate(results):
#     print(f'ROI {i}: Class {class_label} - {text}')

# cv2.imshow('Text Detection', orig_with_boxes)
# cv2.waitKey(0)
# cv2.destroyAllWindows()

import easyocr

# Create a reader instance for Russian and English
reader = easyocr.Reader(['ru'])

# Read the text from the image
results = reader.readtext(
    'D:\\DevDrive\\localzet-dev\\hacks-ai-dpr\\assets\\subm_example\\IMG_20240329_095208778~2.jpg')

print(results)
# text_only = [result[1] for result in results]
#
# # Print the extracted text
# for text in text_only:
#     print(text)

from classificator import Classificator

classificator = Classificator()
print(classificator.predict_category('вапро'))
while True:
    text_input = input()
    print('Товар:', text_input)
    ans = classificator.predict_category(text_input)
    print('Категория:', ans)
