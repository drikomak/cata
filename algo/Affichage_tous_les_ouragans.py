import pandas as pd
import matplotlib.pyplot as plt
import geopandas as gpd
from shapely.geometry import Point

# Chemin vers votre fichier CSV
file_path = 'algo/corrected_hurricane_data.csv'  # Remplacez par le chemin réel de votre fichier

# Chargement des données
data = pd.read_csv(file_path, sep=',')  # Assurez-vous que le séparateur correspond à celui de votre fichier

# Chargement d'une carte du monde pour le fond
fig, ax = plt.subplots(figsize=(12, 8))
world = gpd.read_file(gpd.datasets.get_path('naturalearth_lowres'))
world.plot(ax=ax, color='lightgrey')

# Conversion des données de latitude et longitude en points géographiques
gdf = gpd.GeoDataFrame(data, geometry=gpd.points_from_xy(data['long'], data['lat']))

# Tracé de tous les ouragans du jeu de données par un point sur la carte
gdf.plot(ax=ax, marker='o', color='blue', markersize=5)

plt.title('Répartition des Observations d\'Ouragans')
plt.xlabel('Longitude')
plt.ylabel('Latitude')
plt.grid(True)
plt.show()
