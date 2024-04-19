import numpy as np
import pandas as pd
from matplotlib.path import Path
import matplotlib.pyplot as plt
from prompt_toolkit import HTML
from mpl_toolkits.basemap import Basemap
import tensorflow as tf



df = pd.read_csv('algo/DataSet_jdid.csv')

# Affichage des premières lignes du dataset
print(df.head())

# Affichage des informations sur les colonnes
print(df.info())


# Helper function for Plotting Maps
def MapTemplate(plt_title):
  plt.figure(figsize=(10, 8))
  m1 = Basemap(llcrnrlon=-100.,llcrnrlat=0.,urcrnrlon=30.,urcrnrlat=57.,projection='lcc',lat_1=20.,lat_2=40.,lon_0=-60.,resolution ='l',area_thresh=1000.)    
  m1.drawcoastlines()
  m1.drawparallels(np.arange(10,70,10),labels=[1,1,0,0])
  m1.drawmeridians(np.arange(-100,0,10),labels=[0,0,0,1])
  plt.suptitle(plt_title, fontsize=16)
  return m1


def FeatureColumnsXYZ(df):
    df['x'] = np.cos(np.radians(df.Lat)) * np.cos(np.radians(df.Long))
    df['y'] = np.cos(np.radians(df.Lat)) * np.sin(np.radians(df.Long))
    df['z'] = np.sin(np.radians(df.Lat))

    df['LatLag1'] = df['Lat'].shift(1)
    df['LonLag1'] = df['Long'].shift(1)

    
    df['xLag1'] = np.cos(np.radians(df.LatLag1)) * np.cos(np.radians(df.LonLag1))
    df['yLag1'] = np.cos(np.radians(df.LatLag1)) * np.sin(np.radians(df.LonLag1))
    df['zLag1'] = np.sin(np.radians(df.LatLag1))

    print(df.head(10))
FeatureColumnsXYZ(df)


def FeatureColumns(df):
    grid1 = [(-70.0,5.0),(-70.0,20.0),(-10.0,20.0),(-10.0,5.0),(-70.0,5.0)]
    grid2 = [(-100.0,5.0),(-100.0,20.0),(-70.0,20.0),(-70.0,5.0),(-100.0,5.0)]
    grid3 = [(-70.0,20.0),(-70.0,40.0),(-10.0,40.0),(-10.0,20.0),(-70.0,20.0)]
    grid4 = [(-100.0,20.0),(-100.0,40.0),(-70.0,40.0),(-70.0,20.0),(-100.0,20.0)]
    poly1 = Path(grid1)
    poly2 = Path(grid2)
    poly3 = Path(grid3)
    poly4 = Path(grid4)
    
    Genesis = df.groupby('ID').first()
    Genesis['GroupID'] = 0
    
    for idx, row in Genesis.iterrows():
        point = (row['Long'], row['Lat'])  # Inversion de la latitude et de la longitude pour être cohérent avec les tuples des polygones
        inside1 = poly1.contains_point(point)
        inside2 = poly2.contains_point(point)
        inside3 = poly3.contains_point(point)
        inside4 = poly4.contains_point(point)
        if inside1:
            Genesis.at[idx, 'GroupID'] = 1
        elif inside2:       
            Genesis.at[idx, 'GroupID'] = 2
        elif inside3:
            Genesis.at[idx, 'GroupID'] = 3
        elif inside4:
            Genesis.at[idx, 'GroupID'] = 4
    
    train_events_g1 = Genesis[Genesis['GroupID'] == 1].index.tolist()
    train_events_g2 = Genesis[Genesis['GroupID'] == 2].index.tolist()
    train_events_g3 = Genesis[Genesis['GroupID'] == 3].index.tolist()
    train_events_g4 = Genesis[Genesis['GroupID'] == 4].index.tolist()
    
    df['GroupID'] = 0
    df.loc[df['ID'].isin(train_events_g1), 'GroupID'] = 1
    df.loc[df['ID'].isin(train_events_g2), 'GroupID'] = 2
    df.loc[df['ID'].isin(train_events_g3), 'GroupID'] = 3
    df.loc[df['ID'].isin(train_events_g4), 'GroupID'] = 4

    one_hot = pd.get_dummies(df['GroupID'], prefix='Region')
    df = df.join(one_hot)
    
    return df



def model_def(data):
    model = tf.keras.Sequential()
    init = tf.keras.initializers.RandomUniform(minval=-0.001, maxval=0.001)
    model.add(tf.keras.layers.LSTM(50,input_shape=(None, data.shape[2]), kernel_initializer=init, return_sequences=True))
    model.add(tf.keras.layers.LSTM(10,input_shape=(None, data.shape[2]), kernel_initializer=init, return_sequences=True))
    model.add(tf.keras.layers.Dense(6, kernel_initializer=init))
    model.add(tf.keras.layers.Dense(3, activation='linear', kernel_initializer=init))
    model.compile(loss='mean_squared_error', optimizer='adam', metrics=['acc','mae'])
    return model



def train_on_batch_lstm(data,train_df,NumEpochs):
    model_batch_train = model_def(data)
    for i in range(NumEpochs):
        print ('Running Epoch No: ', i)
        for stormID, data in train_df.groupby('StormID'):
            train = data[['x','y','z','Region_1','Region_2','Region_3','Region_4','S1','S2','S3','S4','S5','S6','S1E', 'S2E', 'S3E', 'S4E', 'S5E', 'S6E','xLag1','yLag1','zLag1']].values
            x_train = np.expand_dims(train[:,:-3], axis=0)
            y_train = np.expand_dims(train[:,-3:], axis=0)
            model_batch_train.train_on_batch(x_train,y_train)
            model_batch_train.reset_states()
    return model_batch_train



models = []
for ens in range(ensemble_size):
    lstm_model = train_on_batch_lstm(data,train_df, nb_epoch)
    # serialize model to JSON
    model_json = lstm_model.to_json()
    with open("gmodel_"+str(ens)+".json", "w") as json_file:
        json_file.write(model_json)
    # serialize weights to HDF5
    lstm_model.save_weights("gmodel_"+str(ens)+".h5")
    models.append("gmodel_"+str(ens)+".h5")
    print("Saved model to disk") 



def PredictHist(key,value,AndTest,models,ensemble_size,NoOfSimPoints):
    SimPath = pd.DataFrame(columns=['Sim','Epoch','Lon','Lat'])
    SimPath['Sim'] = np.repeat(np.arange(ensemble_size),NoOfSimPoints)
    SimPath['Epoch'] = list(np.arange(NoOfSimPoints))*ensemble_size
    SimPath.set_index(['Sim', 'Epoch'],inplace=True)
    
    # Separate the Inputs and Targets
    x_AndTest_full = AndTest[['x','y','z','Region_1','Region_2','Region_3','Region_4','S1','S2','S3','S4','S5','S6','S1E', 'S2E', 'S3E', 'S4E', 'S5E', 'S6E']].values
    y_AndTest_full = AndTest[['xLag1','yLag1','zLag1']].values

    ## data has to be reshaped by (nb_samples,timesteps,NoOfAttributes)
    AndTest_data = x_AndTest_full.reshape(x_AndTest_full.shape[0], 1, x_AndTest_full.shape[1])
    AndTest_target = y_AndTest_full.reshape(y_AndTest_full.shape[0], 1, y_AndTest_full.shape[1])
    
    end = NoOfSimPoints
    
    simval = 0
    for sim,mod in enumerate(models):
        print ('model = ',mod)
        pred_model = model_def(AndTest_data)
        pred_model.load_weights(mod)
        
        # Use the fitted model to make predictions on the test data
        predictions_And_feedback = np.empty((0,3))
        X = AndTest_data[0,0,:]
        Y = AndTest_data[0,0,3:7]
        predictions_And_feedback = np.vstack((predictions_And_feedback,AndTest_data[0,0,0:3]))
        for i in range(NoOfSimPoints-1): 
            yhat = forecast_lstm(pred_model, 1, X)
            Lon_temp = np.degrees(np.arctan2(yhat[0][1],yhat[0][0]))
            Lat_temp = np.degrees(np.arcsin(yhat[0][2]))
            
            GridIdx = gridpts_tree.query([(Lon_temp*1.0,Lat_temp)])[1]
            x_coord = Grid.iloc[GridIdx]['Lon'].values[0]
            y_coord = Grid.iloc[GridIdx]['Lat'].values[0]
            temp = [(0,-1),(-1,-1),(-1,0),(-1,1),(0,1),(1,1)]
            prob = []
            for x, y in [(x_coord+i*0.5, y_coord+j*0.5) for i, j in temp]:
                temp = '('+str(x)+', '+str(y)+')'
                MatchGrid = Grid[Grid.Cell.astype(str).str.contains(temp)]
                if (MatchGrid.shape[0] == 1):
                    prob.append(MatchGrid.CountHits.values.tolist()[0])
                else:
                    prob.append(0)
            if sum(prob) > 0:
                b = [temp/sum(prob) for temp in prob]
            else:
                b = prob
            
            k = np.array(b).reshape(1,6)
            kk = np.zeros((1,6),dtype=int)

            indices = np.where(k == k.max())
            kk[0,indices[1]]=1
            
            predictions_And_feedback = np.vstack((predictions_And_feedback,yhat))
            pred_model.reset_states()

            X = np.hstack((yhat[0],Y,k[0],kk[0]))
        
        SimPath.Lon.loc[pd.IndexSlice[simval,:]] = np.degrees(np.arctan2(predictions_And_feedback[:,1],predictions_And_feedback[:,0]))
        SimPath.Lat.loc[pd.IndexSlice[simval,:]] = np.degrees(np.arcsin(predictions_And_feedback[:,2]))
        end = end+NoOfSimPoints
        simval = simval+1
    SimPath.to_csv(key+'_Pred.csv')
    AndTest.to_csv(key+'_Actual.csv')
    return SimPath
  
TestHist = {'Ivan2004':'AL092004','Wilma2005':'AL252005'}

def PlotStochTrack(HistPath, StochPath, plt_title):
    m1 = MapTemplate(plt_title)
    for stormid, track in HistPath.groupby('StormID'):
        lat_kat = track.Lat.values
        lon_kat = track.Lon.values
        x1, y1 = m1(lon_kat,lat_kat)
        plt.plot(x1,y1,'-',label=stormid,linewidth=2,color='black')
    
    for stormid, track in StochPath.groupby('Sim'):
        lat_kat = track.Lat.values
        lon_kat = track.Lon.values
        poly = np.polyfit(list(lon_kat), list(lat_kat), 5)
        poly_lat = np.poly1d(poly)(lon_kat)
        poly_lat[0] = lat_kat[0]
        x1, y1 = m1(lon_kat,lat_kat)
        plt.plot(x1,y1,'-',label=stormid,linewidth=2)
    plt.legend()

for key, value in TestHist.items():
    HistPath = test_df[test_df.StormID == value]
    SimPath = PredictHist(key,value,HistPath,models,ensemble_size,NoOfSimPoints)
    PlotStochTrack(HistPath, SimPath, key)
