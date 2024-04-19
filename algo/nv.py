import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
from mpl_toolkits.basemap import Basemap

# Helper function for Plotting Maps
def MapTemplate(plt_title):
    plt.figure(figsize=(10, 8))
    m1 = Basemap(llcrnrlon=-100.,llcrnrlat=0.,urcrnrlon=30.,urcrnrlat=57.,
                 projection='lcc',lat_1=20.,lat_2=40.,lon_0=-60.,resolution ='l',
                 area_thresh=1000.)    
    m1.drawcoastlines()
    m1.drawparallels(np.arange(10,70,10),labels=[1,1,0,0], color = "red")
    m1.drawmeridians(np.arange(-100,0,10),labels=[0,0,0,1], color = "red")
    plt.suptitle(plt_title, fontsize=16)
    return m1

# Charger les données depuis le fichier CSV
dataset = pd.read_csv("algo/DataSet_Jdid.csv")

andrew_data = dataset[dataset['ID'] == 'AL071989']

# Extraire les coordonnées de latitude et de longitude de la trajectoire d'ANDREW
andrew_lons = andrew_data['Long']
andrew_lats = andrew_data['Lat']

# Utilisation de la fonction pour créer la carte
map_title = "Trajectoire de l'ouragan ERIN"
map_instance = MapTemplate(map_title)

# Tracer la grille
parallels = np.arange(0.,60.,10.)
meridians = np.arange(-100.,40.,10.)
map_instance.drawparallels(parallels, labels=[1,0,0,0], linewidth=0.5, color='gray', dashes=[1, 1])
map_instance.drawmeridians(meridians, labels=[0,0,0,1], linewidth=0.5, color='gray', dashes=[1, 1])

# Tracer la trajectoire de l'ouragan ANDREW
map_instance.plot(andrew_lons, andrew_lats, marker='o', markersize=2, color='red', latlon=True)

# Afficher la carte
plt.show()


