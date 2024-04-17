import Basemap
import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
from prompt_toolkit import HTML


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

# Cyclicality
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


def HurrSeasonality(df):
    grouped_by_month = df.groupby('Month')['ID'].unique().to_frame().reset_index()
    grouped_by_month['NumStorms'] = grouped_by_month['ID'].str.len()
    grouped_by_month['Month'] = grouped_by_month['Month'].astype('int')
    grouped_by_month['NumStorms'] = grouped_by_month['NumStorms'].astype('int')
    plt.rcParams["figure.figsize"] =(20,5)
    grouped_by_month.plot.bar(x='Month', y='NumStorms')
    plt.ylabel('No. of Storms')
    plt.suptitle('Storm Frequency by Month', fontsize=23)
    plt.show()

HurrSeasonality(df)


# Most Frequent Storm Name
def MostFreqStormNames(df):
  UniqueStorms = df.groupby(['ID','Name']).size().reset_index().rename(columns={0:'NoRecordings'})
  NameFreq = UniqueStorms.StormName.value_counts().reset_index().rename(columns={'index':'StrmName', 'StormName':'StrmCount'})
  HTML(NameFreq[:10].to_html())


# Helper function for Plotting Maps
def MapTemplate(plt_title):
  plt.figure(figsize=(10, 8))
  m1 = Basemap(llcrnrlon=-100.,llcrnrlat=0.,urcrnrlon=30.,urcrnrlat=57.,projection='lcc',lat_1=20.,lat_2=40.,lon_0=-60.,resolution ='l',area_thresh=1000.)    
  m1.drawcoastlines()
  m1.drawparallels(np.arange(10,70,10),labels=[1,1,0,0])
  m1.drawmeridians(np.arange(-100,0,10),labels=[0,0,0,1])
  plt.suptitle(plt_title, fontsize=16)
  return m1

