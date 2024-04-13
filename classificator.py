import os

import joblib

from trainer import Trainer


class Classificator:
    def __init__(self):
        self.trainer = Trainer()
        self.model, self.vectorizer = self._load_model_and_vectorizer()

    def _load_model_and_vectorizer(self):
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
        return self.predict_categories(product_name)[0]

    def predict_categories(self, product_name):
        if not self.model or not self.vectorizer:
            print('Модель или векторизатор не загружены. Пожалуйста, обучите модель.')
            return None

        vectorized_name = self.vectorizer.transform([product_name]).toarray()
        prediction = self.model.predict(vectorized_name)

        return prediction
