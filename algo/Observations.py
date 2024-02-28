import pandas as pd
import matplotlib.pyplot as plt


df = pd.read_csv('algo/DataSet_jdid.csv')

df_unique_category = df.groupby('ID')['Category'].max().reset_index()


category_distribution_unique = df_unique_category['Category'].value_counts().sort_index()
print(category_distribution_unique)

plt.figure(figsize=(10, 6))
category_distribution_unique.plot(kind='bar', color='skyblue')

plt.title('Distribution des Catégories pour les Ouragans Uniques')
plt.xlabel('Catégorie')
plt.ylabel('Nombre d\'Ouragans')
plt.xticks(rotation=0)

plt.show()

