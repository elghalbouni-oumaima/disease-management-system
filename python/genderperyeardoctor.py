import matplotlib.pyplot as plt
import mysql.connector
import pandas as pd
import numpy as np
import os
import sys
from dotenv import load_dotenv
from pathlib import Path
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
        query="""
        select year(registration_day) as year,
        sum(case when Gender='Female' then 1 else 0 end) as Female,
        sum(case when Gender='Male' then 1 else 0 end) as Male
        from patient 
        where doctor_id=%s
        group by year
        order by year
        limit 6;"""
        df=pd.read_sql(query,conn,params=[doctor_id])
        index = np.arange(len(df))
        print(df)

        # Largeur des barres
        bar_width = 0.35
        # Création du graphique
        plt.figure(figsize=(10, 6))
        fbars=plt.bar(index, df['Female'], bar_width, label='Female', color='pink')
        mbars=plt.bar(index + bar_width, df['Male'], bar_width, label='Male', color='skyblue')

        # Personnalisation
        #plt.title('Number of Female and Male Patients per Year')
        plt.xlabel('Year')
        plt.ylabel('Number of Patients')
        plt.legend()


                
        plt.xticks(index + bar_width / 2, df['year'])
        #Matplotlib optimise automatiquement l’espacement autour des éléments du graphique, pour que tout rentre proprement dans la figure
        plt.tight_layout()
        for bar in fbars:
                height = bar.get_height()
                plt.annotate(f'{int(height)}',
                        xy=(bar.get_x() + bar.get_width() / 2, height),
                        xytext=(0, 3),  # décalage vertical
                        textcoords="offset points",
                        ha='center', va='bottom', fontsize=9)
        for bar in mbars:
                height = bar.get_height()
                plt.annotate(f'{int(height)}',
                        xy=(bar.get_x() + bar.get_width() / 2, height),
                        xytext=(0, 3),  # décalage vertical
                        textcoords="offset points",
                        ha='center', va='bottom', fontsize=9)
        # sauvgarde le fich
        # Supprimer les  bordures (top, right)
        plt.gca().spines['top'].set_visible(False)
        plt.gca().spines['right'].set_visible(False)
        #plt.show()
        if len(sys.argv) > 2 and sys.argv[2]=="1":
                plt.show()
        else:
                # Crée le dossier s'il n'existe pas
                os.makedirs("images", exist_ok=True)
                # Sauvegarder l'image dans le dossier 'images'
                plt.savefig("images/genderperyeardoctor.png")

        
except mysql.connector.Error as err:
        print(f"Erreur : {err}")


finally:
        conn.close()