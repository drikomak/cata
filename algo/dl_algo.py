import matplotlib.pyplot as plt
import pandas as pd


hurdat2 = pd.read_csv('hurdat2.txt')




def StormCountByCategory(hurdat2):
  grouped = hurdat2.groupby('Cat')['Name'].unique().to_frame().reset_index()
  grouped['Num'] = grouped['Name'].str.len()
  grouped.plot.bar(x='Cat', y='Num')
  plt.ylabel('Number of Storms')

