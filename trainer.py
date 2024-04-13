import os

import joblib
import pandas as pd
from sklearn.ensemble import RandomForestClassifier
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics import accuracy_score
from sklearn.model_selection import train_test_split


class Trainer:
    def __init__(self, csv_path='assets/train.csv', n_estimators=100, random_state=42, test_size=0.2):
        self.csv_path = csv_path
        self.model_path = self._replace_extension(csv_path, '.zmodel')
        self.vectorizer_path = self._replace_extension(csv_path, '.zvectorizer')
        self.n_estimators = n_estimators
        self.random_state = random_state
        self.test_size = test_size

    def _replace_extension(self, path, new_extension):
        return os.path.splitext(path)[0] + new_extension

    def _load_data(self):
        df = pd.read_csv(self.csv_path, sep=';', encoding='utf-8')
        return df['Наименование товара'].values, df['Категория продукта'].values

    def _prepare_data(self):
        product_names, categories = self._load_data()
        vectorizer = TfidfVectorizer()
        vectors = vectorizer.fit_transform(product_names).toarray()
        return vectors, categories, vectorizer

    def _train_model(self, x_train, y_train):
        model = RandomForestClassifier(n_estimators=self.n_estimators,
                                       random_state=self.random_state)
        model.fit(x_train, y_train)
        return model

    def _evaluate_model(self, model, x_test, y_test):
        predictions = model.predict(x_test)
        return accuracy_score(y_test, predictions)

    def train_model(self):
        x, y, vectorizer = self._prepare_data()
        x_train, x_test, y_train, y_test = train_test_split(x, y,
                                                            test_size=self.test_size,
                                                            random_state=self.random_state)

        if os.path.exists(self.model_path) and os.path.exists(self.vectorizer_path):
            model = joblib.load(self.model_path)
            vectorizer = joblib.load(self.vectorizer_path)
            print(f'Модель и векторизатор загружены из {self.model_path} и {self.vectorizer_path}')
            return model, vectorizer
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
        x_new, y_new, vectorizer = self._prepare_data()

        if os.path.exists(self.model_path) and os.path.exists(self.vectorizer_path):
            model = joblib.load(self.model_path)
            model.fit(x_new, y_new)
            joblib.dump(model, self.model_path)
            print('Модель обновлена новыми данными')
            return model, vectorizer
        else:
            return self.train()
