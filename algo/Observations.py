import numpy as np
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


def HurrCyclicality(df):
  grouped_by_year = df.groupby('Year')['ID'].unique().to_frame().reset_index()
  grouped_by_year['NumStorms'] = grouped_by_year['ID'].str.len()
  grouped_by_year['Year'] = grouped_by_year['Year'].astype('int')
  grouped_by_year['NumStorms'] = grouped_by_year['NumStorms'].astype('int')
  plt.rcParams["figure.figsize"] =(20,5)
  grouped_by_year.plot(x='Year', y='NumStorms',linewidth=3,linestyle = 'dashed')
  plt.xticks(np.arange(grouped_by_year.Year.min(),grouped_by_year.Year.max(),25))
  plt.ylabel('No. of Storms')
  plt.plot(grouped_by_year['Year'],grouped_by_year['NumStorms'].rolling(10).mean(),label= 'MA 10 years', linewidth=3)
  plt.plot(grouped_by_year['Year'],grouped_by_year['NumStorms'].rolling(25).mean(),label= 'MA 25 years', linewidth=3)
  plt.ylabel('No. of Storms')
  plt.xlabel('Year No.')
  plt.legend()

  plt.show()

HurrCyclicality(df)

