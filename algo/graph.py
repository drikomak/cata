import numpy as np
import pandas as pd
from matplotlib.path import Path
import matplotlib.pyplot as plt
from prompt_toolkit import HTML
from mpl_toolkits.basemap import Basemap
import tensorflow as tf



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


# Storm Track of All Storms in Atlantic Basin
def AllStormTracks(df):
  m1 = MapTemplate("Atlantic Hurricane Historical Storms (1851-2014)")
  UniqueStorms = df.ID.unique()
  for stormid, track in df.groupby('ID'):
      lat_storm = track.Lat.values
      lon_storm = track.Long.values
      x1, y1 = m1(lon_storm,lat_storm)
      plt.plot(x1,y1,'r-',linewidth=0.5)
  plt.title('Total Number of Storms = '+ str(len(UniqueStorms)))
  plt.show()



def StormGenesis(df):
  m = MapTemplate("Atlantic Hurricane Storm Genesis (1851-2014)")
  UniqueStorms = df.ID.unique()
  Genesis = df.groupby('ID').first()
  longitude = Genesis.Long.values
  latitude = Genesis.Lat.values
  cat = Genesis.Category.values
  x, y = m(longitude, latitude)
  plt.scatter(x,y,color='m',marker='D',s=4)
  plt.title('Total Number of Unique Storms = %d' % (len(UniqueStorms)))
  plt.show()

StormGenesis(df)


# Visualisation de la séparation des 4 groupes 
def separations():
    grid1 = [(-70.0,5.0),(-70.0,20.0),(-10.0,20.0),(-10.0,5.0),(-70.0,5.0)]
    grid2 = [(-100.0,5.0),(-100.0,20.0),(-70.0,20.0),(-70.0,5.0),(-100.0,5.0)]
    grid3 = [(-70.0,20.0),(-70.0,40.0),(-10.0,40.0),(-10.0,20.0),(-70.0,20.0)]
    grid4 = [(-100.0,20.0),(-100.0,40.0),(-70.0,40.0),(-70.0,20.0),(-100.0,20.0)]

    # Création des objets Path pour chaque polygone
    poly1 = Path(grid1)
    poly2 = Path(grid2)
    poly3 = Path(grid3)
    poly4 = Path(grid4)

    # Création de la carte
    m = MapTemplate("Limites des groupes")

    # Tracé des limites des polygones
    x1, y1 = zip(*grid1)
    x2, y2 = zip(*grid2)
    x3, y3 = zip(*grid3)
    x4, y4 = zip(*grid4)
    m.plot(x1, y1, latlon=True, color='blue', label='Region 1')
    m.plot(x2, y2, latlon=True, color='green', label='Region 2')
    m.plot(x3, y3, latlon=True, color='red', label='Region 3')
    m.plot(x4, y4, latlon=True, color='orange', label='Region 4')

    # Ajout de la légende
    plt.legend()

    # Affichage de la carte
    plt.show()
separations()