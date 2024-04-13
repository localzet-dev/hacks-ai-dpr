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
#
# cv2.imshow('Text Detection', orig_with_boxes)
# cv2.waitKey(0)
# cv2.destroyAllWindows()




from classificator import Classificator

classificator = Classificator()
print(classificator.predict_category('Альпина Ракушечки'))
