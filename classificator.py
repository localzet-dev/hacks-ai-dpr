import os

import joblib

from trainer import Trainer


class Classificator:
    """
    Класс Classificator для классификации названий продуктов по категориям.
    """

    def __init__(self):
        """
        Инициализирует объект Classificator, загружая модель и векторизатор.
        """
        self.trainer = Trainer()
        self.model, self.vectorizer = self._load_model_and_vectorizer()

    def _load_model_and_vectorizer(self):
        """
        Загружает модель и векторизатор, если они существуют, иначе начинает процесс обучения.
        """
        if os.path.exists(self.trainer.model_path) and os.path.exists(self.trainer.vectorizer_path):
            model = joblib.load(self.trainer.model_path)
            vectorizer = joblib.load(self.trainer.vectorizer_path)
            print('Модель и векторизатор успешно загружены.')
            return model, vectorizer
        else:
            print('Файлы модели и векторизатора не найдены. Начинаем процесс обучения.')
            self.trainer.train_model()
            return joblib.load(self.trainer.model_path), joblib.load(self.trainer.vectorizer_path)

    def predict_category(self, product_name):
        """
        Предсказывает категорию для одного названия продукта.
        """
        return self.predict_categories([product_name])[0]

    def predict_categories(self, product_names):
        """
        Предсказывает категории для списка названий продуктов.
        """
        if not self.model or not self.vectorizer:
            print('Модель или векторизатор не загружены. Пожалуйста, обучите модель.')
            return None

        vectorized_names = self.vectorizer.transform(product_names).toarray()
        predictions = self.model.predict(vectorized_names)

        return predictions



classificator = Classificator()
while True:
    text_input = input()
    print('Товар:', text_input)
    ans = classificator.predict_category(text_input)
    print('Категория:', ans)
