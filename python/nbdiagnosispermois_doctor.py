import matplotlib.pyplot as plt
import mysql.connector
import pandas as pd
import numpy as np
import os
import sys
from dotenv import load_dotenv
from pathlib import Path
from datetime import datetime
from scipy.interpolate import make_interp_spline

def generate_courbe(conn,j,doctor_id):
        query=""" select  year(diagnosis_date) as diagyear,MONTH(diagnosis_date)as monthdiag,count(*) as nb from diagnosis
                where doctor_id=%s
                group by diagyear,monthdiag
                order by diagyear, monthdiag; """
        df=pd.read_sql_query(query,conn,params=[doctor_id])
        print(df)
        
        #plt.plot(df['monthdiag'],df['nb'],label='Courbe 1', color='blue', marker='o')
        dates=[]
        for itm in df['monthdiag']:
            match itm:
                case 1:
                    dates.append("January") 
                case 2:
                    dates.append("February")
                case 3:
                    dates.append("March")
                case 4:
                    dates.append("April")
                case 5:
                    dates.append("May")
                case 6:
                    dates.append("June")
                case 7:
                    dates.append("July")
                case 8:
                    dates.append("August")
                case 9:
                    dates.append("September")
                case 10:
                    dates.append("October")
                case 11:
                    dates.append("November")
                case 12:
                    dates.append("December")
                

        x_num=df['monthdiag']
        y=df['nb']

        # Création d'une spline pour lisser la courbe
        x_smooth = np.linspace(x_num.min(), x_num.max(), 200)  # plus de points pour une courbe fluide
        spl = make_interp_spline(x_num, y, k=j)  # k=3 pour une courbe douce (cubic spline)
        y_smooth = spl(x_smooth)
        plt.scatter(x_num, y)  # Afficher les vrais points si tu veux
        plt.plot(x_smooth,y_smooth)
        #plt.fill_between(x_smooth, y_smooth,  alpha=0.15,color="#44b0f8")  # Remplissage doux
        return {'dates':dates,'month':df['monthdiag']}
if len(sys.argv) > 1 :
    doctor_id= sys.argv[1]
else:
    exit()
if not load_dotenv(dotenv_path = Path('C:/xampp/htdocs/managementdesease/Login/.env')):
        print( "Erreur : Impossible de charger le fichier .env")
           
try:
        conn = mysql.connector.connect(
            host=os.getenv('localhost'),
            user=os.getenv('DB_user_name'),
            password=os.getenv('DB_PASSWORD'),
            database="diseasemanagement"
        )
        
        dict1=generate_courbe(conn,1,doctor_id)
        dates=dict1['dates'] 
        monthdiag =dict1['month'] 
        plt.xticks(ticks=monthdiag, labels=dates)
        #plt.title(f"Monthly Distribution of Diagnoses: ",fontsize=14,fontweight='bold')
        plt.xlabel('Months',fontsize=13)
        plt.ylabel('Number of Diagnoses',fontsize=13)
        plt.legend()  # pour afficher la légende
        plt.gca().spines['top'].set_visible(False)
        plt.gca().spines['right'].set_visible(False)
        #plt.show()
        if len(sys.argv) > 2 and sys.argv[2]=="1":
                plt.show()
        else:
                # Crée le dossier s'il n'existe pas
                os.makedirs("images", exist_ok=True)
                # Sauvegarder l'image dans le dossier 'images'
                plt.savefig("images/nbdiagnosispermois_doctor.png")    
except mysql.connector.Error as err:
        print(f"Erreur : {err}")