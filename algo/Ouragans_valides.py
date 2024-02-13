import numpy as np
import pandas as pd

# Chargement des données
data = pd.read_csv('algo/corrected_hurricane_data.csv', sep=',')

# Fonction pour calculer l'angle entre deux vecteurs
def angle_between_vectors_degrees(v1, v2):
    unit_vector_1 = v1 / np.linalg.norm(v1)
    unit_vector_2 = v2 / np.linalg.norm(v2)
    dot_product = np.dot(unit_vector_1, unit_vector_2)
    angle = np.arccos(dot_product)
    return np.degrees(angle)

# Liste pour stocker les noms des ouragans valides
valid_ouragans = []

# Itérer à travers chaque ouragan
for name in data['name'].unique():
    ouragan_data = data[data['name'] == name].sort_values(by=['year', 'month', 'day'])
    angles = []
    
    # Calcul des angles pour chaque segment consécutif
    for i in range(len(ouragan_data) - 2):
        p1, p2, p3 = ouragan_data.iloc[i:i+3][['long', 'lat']].values
        v1 = p2 - p1
        v2 = p3 - p2
        angle = angle_between_vectors_degrees(v1, v2)
        angles.append(angle)
    
    # Vérification si l'ouragan a un angle > 90°
    if all(angle <= 90 for angle in angles):
        valid_ouragans.append(name)

print("Ouragans sans angles > 90° entre les segments de trajectoire :", valid_ouragans)
