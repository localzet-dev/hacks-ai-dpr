import os

import joblib
import pandas as pd
from sklearn.ensemble import RandomForestClassifier
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics import accuracy_score
from sklearn.model_selection import train_test_split


class Trainer:
    """
    Класс Trainer для обучения и обновления модели классификатора RandomForest
    с использованием векторизации TF-IDF на данных о названиях и категориях продуктов.
    """

    def __init__(self, csv_path='assets/train.csv', n_estimators=100, random_state=42, test_size=0.2):
        self.csv_path = csv_path
        self.model_path = self._replace_extension(csv_path, '.zmodel')
        self.vectorizer_path = self._replace_extension(csv_path, '.zvectorizer')
        self.n_estimators = n_estimators
        self.random_state = random_state
        self.test_size = test_size

    def _replace_extension(self, path, new_extension):
        """
        Заменяет расширение файла на новое.
        """
        return os.path.splitext(path)[0] + new_extension

    def _load_data(self):
        """
        Загружает данные из CSV-файла и возвращает названия продуктов и категории.
        """
        df = pd.read_csv(self.csv_path, sep=';', encoding='utf-8')
        return df['Наименование товара'].values, df['Категория продукта'].values

    def _prepare_data(self):
        """
        Подготавливает данные, векторизуя названия продуктов с помощью TF-IDF,
        и возвращает векторы, категории и векторизатор.
        """
        product_names, categories = self._load_data()
        vectorizer = TfidfVectorizer()
        vectors = vectorizer.fit_transform(product_names).toarray()
        return vectors, categories, vectorizer

    def _train_model(self, x_train, y_train):
        """
        Обучает модель классификатора случайного леса с тренировочными данными.
        """
        model = RandomForestClassifier(n_estimators=self.n_estimators,
                                       random_state=self.random_state)
        model.fit(x_train, y_train)
        return model

    def _evaluate_model(self, model, x_test, y_test):
        """
        Оценивает обученную модель с использованием тестовых данных и возвращает показатель точности.
        """
        predictions = model.predict(x_test)
        return accuracy_score(y_test, predictions)

    def train_model(self):
        """
        Обучает или загружает модель и векторизатор, если они существуют. Возвращает модель
        и векторизатор после обучения или загрузки.
        """
        x, y, vectorizer = self._prepare_data()
        x_train, x_test, y_train, y_test = train_test_split(x, y,
                                                            test_size=self.test_size,
                                                            random_state=self.random_state)

        # Проверка существования файлов только один раз
        model_exists = os.path.exists(self.model_path)
        vectorizer_exists = os.path.exists(self.vectorizer_path)

        if model_exists and vectorizer_exists:
            model = joblib.load(self.model_path)
            vectorizer = joblib.load(self.vectorizer_path)
            print(f'Модель и векторизатор загружены из {self.model_path} и {self.vectorizer_path}')
        else:
            model = self._train_model(x_train, y_train)
            accuracy = self._evaluate_model(model, x_test, y_test)
            print(f'Модель обучена. Точность модели: {accuracy}')
            if accuracy > 0.75:
                joblib.dump(model, self.model_path)
                joblib.dump(vectorizer, self.vectorizer_path)
                print(f'Модель и векторизатор сохранены в {self.model_path} и {self.vectorizer_path}')

        return model, vectorizer

    def update_model(self):
        """
        Обновляет модель новыми данными.
        """
        x_new, y_new, vectorizer = self._prepare_data()

        if os.path.exists(self.model_path):
            model = joblib.load(self.model_path)
            model.fit(x_new, y_new)
            joblib.dump(model, self.model_path)
            print('Модель обновлена новыми данными')
            return model, vectorizer
        else:
            return self.train_model()
