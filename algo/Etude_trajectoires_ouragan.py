import pandas as pd
import matplotlib.pyplot as plt
import geopandas as gpd

file_path = 'algo/corrected_hurricane_data.csv'

data = pd.read_csv(file_path, sep=',')
ouragan_choisi = "Cristobal" 

if ouragan_choisi not in data['name'].values:
    print(f"L'ouragan '{ouragan_choisi}' n'est pas dans le jeu de données.")
else:
    ouragan_data = data[data['name'] == ouragan_choisi]
    ouragan_data_sorted = ouragan_data.sort_values(by=['year', 'month', 'day'])

    world = gpd.read_file(gpd.datasets.get_path('naturalearth_lowres'))

    fig, ax = plt.subplots(figsize=(10, 6))
    world.plot(ax=ax, color='lightgrey')

    for i in range(len(ouragan_data_sorted) - 1):
        start_point = ouragan_data_sorted.iloc[i]
        end_point = ouragan_data_sorted.iloc[i + 1]
        ax.annotate('', xy=(end_point['long'], end_point['lat']), xytext=(start_point['long'], start_point['lat']),
                    arrowprops=dict(arrowstyle="->", color='red'))
        
        midpoint = ((start_point['long'] + end_point['long']) / 2, (start_point['lat'] + end_point['lat']) / 2)
        ax.text(midpoint[0], midpoint[1], str(i+1), color='blue', fontsize=9, ha='center', va='center')

    plt.title(f"Trajectoire de l'Ouragan {ouragan_choisi} avec Orientation et Ordre")
    plt.xlabel('Longitude')
    plt.ylabel('Latitude')
    plt.grid(True)
    plt.show()

    evolution_parametres = ouragan_data_sorted[['year', 'month', 'day', 'pressure', 'exact_sst_anomaly', 'wind']]
    print(evolution_parametres)
