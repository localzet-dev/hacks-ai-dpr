import pandas as pd


def create_submission(data, path):
    df = pd.DataFrame(data)
    df.to_csv(path, sep=';', encoding='utf-8', index=False)
