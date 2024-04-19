import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
from mpl_toolkits.basemap import Basemap
from scipy import spatial

# Chargement du dataset
df = pd.read_csv("algo/DataSet_Jdid.csv")

# Helper function for Plotting Maps
def MapTemplate(plt_title):
    plt.figure(figsize=(10, 8))
    m1 = Basemap(llcrnrlon=-100.,llcrnrlat=0.,urcrnrlon=30.,urcrnrlat=57.,
                 projection='lcc',lat_1=20.,lat_2=40.,lon_0=-60.,resolution ='l',
                 area_thresh=1000.)    
    m1.drawcoastlines()
    m1.drawparallels(np.arange(10,70,10),labels=[1,1,0,0])
    m1.drawmeridians(np.arange(-100,0,10),labels=[0,0,0,1])
    plt.suptitle(plt_title, fontsize=16)
    return m1

def Calc_Trans_Prob(Grid):
    MasterGridLookup = {}
    MasterTransProb = {}
    for index, row in Grid.iterrows():
        result = {index:[]}
        prob = {index:[]}
        x_coord = row['Long']
        y_coord = row['Lat']
        total = 0.0
        temp = [(-1, 1), (-1, 0), (-1, -1), (0, 1)]
        for x, y in [(x_coord + i, y_coord + j) for i, j in temp]:
            # Recherche des cellules adjacentes en fonction des coordonnées (latitude et longitude)
            MatchGrid = Grid[(Grid['Long'] == x) & (Grid['Lat'] == y)]
            if not MatchGrid.empty:
                result[index].append(MatchGrid['GridID'].values[0])
                prob[index].append(MatchGrid['CountHits'].values[0])
                total += MatchGrid['CountHits'].values[0]
        if total > 0.0:
            norm_prob = np.array(list(prob.values())).flatten() / total
            prob[index] = norm_prob.tolist()
        MasterGridLookup.update(result)
        MasterTransProb.update(prob)
    return MasterGridLookup, MasterTransProb


# Correction de la fonction TrackSim
def TrackSim(NoOfSim, NoOfSimPoints, Grid, EventData, MasterGridLookup, MasterTransProb):
    # Utiliser les colonnes Long et Lat pour construire l'arbre KD
    gridpts_tree = spatial.KDTree(Grid[['Long', 'Lat']].values)
    SimPath = pd.DataFrame(columns=['Sim','Epoch','Long','Lat'])
    SimPath['Sim'] = np.repeat(np.arange(NoOfSim), NoOfSimPoints)
    SimPath['Epoch'] = np.tile(np.arange(NoOfSimPoints), NoOfSim)
    SimPath.set_index(['Sim', 'Epoch'], inplace=True)
    
    for sim in range(NoOfSim):
        for i in range(NoOfSimPoints):
            if i == 0:
                # Pour la première itération, utiliser les coordonnées initiales de l'événement
                NextCell_Lon, NextCell_Lat = EventData.Long.values[0], EventData.Lat.values[0]
            else:
                # Utiliser l'index de la cellule précédente pour déterminer la prochaine cellule
                GridIdx = gridpts_tree.query([(NextCell_Lon, NextCell_Lat)])[1]
                GridID_Val = Grid.iloc[GridIdx]['GridID'].values[0]
                TransProb = MasterTransProb[GridID_Val]
                
                # Vérifier si la somme des probabilités de transition est supérieure à 0
                if np.sum(TransProb) > 0:
                    cell_direction = np.random.choice(np.arange(len(TransProb)), p=TransProb)
                    NextCell_idx = MasterGridLookup[GridID_Val][cell_direction]
                    NextCell_Lon = Grid.iloc[NextCell_idx]['Long']
                    NextCell_Lat = Grid.iloc[NextCell_idx]['Lat']
                # Si la somme des probabilités est nulle, conserver les coordonnées actuelles
                else:
                    NextCell_Lon, NextCell_Lat = SimPath.loc[(sim, i - 1), ['Long', 'Lat']]
                    
            # Vérifier que la cellule générée reste à l'intérieur de la zone cartographiée
            if not (EventData['Long'].min() <= NextCell_Lon <= EventData['Long'].max() and
                    EventData['Lat'].min() <= NextCell_Lat <= EventData['Lat'].max()):
                # Si la cellule est en dehors de la zone, conserver les coordonnées actuelles
                NextCell_Lon, NextCell_Lat = SimPath.loc[(sim, i - 1), ['Long', 'Lat']]
                
            SimPath.loc[(sim, i), ['Long', 'Lat']] = NextCell_Lon, NextCell_Lat
    
    return SimPath


# Utiliser une interpolation linéaire pour les trajectoires stochastiques
def PlotStochTrack(HistPath, StochPath, plt_title):
    m1 = MapTemplate(plt_title)
    for stormid, track in HistPath.groupby('ID'):
        lat_kat = track.Lat.values
        lon_kat = track.Long.values
        x1, y1 = m1(lon_kat, lat_kat)
        plt.plot(x1, y1, '-', label=stormid, linewidth=2, color='black')

    for sim, track in StochPath.groupby('Sim'):
        lat_kat = track.Lat.values
        lon_kat = track.Long.values
        # Utiliser une interpolation linéaire pour connecter les points de la trajectoire stochastique
        x1, y1 = m1(lon_kat, lat_kat)
        plt.plot(x1, y1, '-', label=sim, linewidth=2)
    
    plt.legend()

# Paramètres de simulation
NoOfSim = 10  # Nombre de simulations
NoOfSimPoints = 50  # Nombre de points dans chaque simulation

# Charger le dataset
df = pd.read_csv("algo/DataSet_Jdid.csv")

# Extraire les données spécifiques à l'ouragan DANNY (ID : AL042015)
danny_data = df[df['ID'] == 'AL042015']

# Calculer les probabilités de transition
MasterGridLookup, MasterTransProb = Calc_Trans_Prob(danny_data)

# Simuler les trajectoires
simulated_paths = TrackSim(NoOfSim, NoOfSimPoints, danny_data, danny_data, MasterGridLookup, MasterTransProb)

# Tracer les trajectoires simulées ainsi que les trajectoires historiques de l'ouragan DANNY
plt.figure(figsize=(10, 8))
plt_title = "Simulation de trajectoires pour l'ouragan DANNY (AL042015)"
PlotStochTrack(danny_data, simulated_paths, plt_title)
plt.show()
