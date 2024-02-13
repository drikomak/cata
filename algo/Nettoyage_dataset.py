import pandas as pd


def convert_coordinates(coord):
    if 'N' in coord or 'E' in coord:
        return float(coord[:-1])
    else:
        return -float(coord[:-1])

processed_data = []

with open('algo/hurdat2.txt', 'r') as file:
    for line in file:
        if line.startswith('AL'):
            parts = line.split(',')
            storm_id = parts[0].strip()
            storm_name = parts[1].strip()
            year = storm_id[4:8]
        else:
            parts = line.split(',')
            month = parts[0][4:6]
            day = parts[0][6:8]
            hour = parts[1].strip()
            record_id = parts[2].strip()
            status = parts[3].strip()
            lat = parts[4].strip()
            lon = parts[5].strip()
            max_wind = parts[6].strip()
            central_pressure = parts[7].strip()
            wind_radii = parts[8:-1]  
            
            processed_data.append([storm_name, year, month, day, hour, lat, lon, max_wind, central_pressure] + wind_radii)

columns = ['Name', 'Year', 'Month', 'Day', 'Hour', 'Lat', 'Long', 'MaxWind', 'CentralPressure',
           'NE34', 'SE34', 'SW34', 'NW34', 'NE50', 'SE50', 'SW50', 'NW50', 'NE64', 'SE64', 'SW64', 'NW64']
df = pd.DataFrame(processed_data, columns=columns)

df['Lat'] = df['Lat'].apply(convert_coordinates)
df['Long'] = df['Long'].apply(convert_coordinates)


df.to_csv('processed_hurdat2_algebrized.csv', index=False)

print("CSV file has been created: processed_hurdat2_algebrized.csv")
