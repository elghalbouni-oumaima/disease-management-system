import matplotlib.pyplot as plt
import mysql.connector
import pandas as pd
import numpy as np
import os
import sys
from dotenv import load_dotenv
from pathlib import Path

if not load_dotenv(dotenv_path = Path('C:/xampp/htdocs/managementdesease/Login/.env')):
        print( "Erreur : Impossible de charger le fichier .env")
           
try:
        conn = mysql.connector.connect(
            host=os.getenv('localhost'),
            user=os.getenv('DB_user_name'),
            password=os.getenv('DB_PASSWORD'),
            database="diseasemanagement"
        )
        query=""" select diagnosis_name,treatment_name,nb from (
                select d.diagnosis_name,
                count(*) as nb ,
                row_number () over (partition by d.diagnosis_name order by count(*) desc) as rn,
                t.treatment_name 
                from treatments t,diagnosis d 
                where t.diagnosis_id=d.diagnosis_id 
                group by d.diagnosis_name,t.treatment_name  
                ) as t where rn=1 order by nb desc limit 6;"""
        df=pd.read_sql(query,conn)
        
        
        # Largeur des barres
        bar_width = 0.35
        # Création du graphique
        plt.figure(figsize=(10, 6))
        index = np.arange(len(df))
        bars=plt.bar(index, df['nb'], bar_width, label='Patient', color='#3f51b5')

        # Personnalisation
        #plt.title('Most Frequently Used Treatment for Each Diagnosis',fontsize=14,fontweight='bold')
        plt.xlabel('Diagnoses',fontsize=12)
        plt.ylabel('Frequency of Treatment Application',fontsize=12)
        plt.legend()

        plt.xticks(index, df['diagnosis_name'])
        # Ajouter les valeurs au-dessus des barres
        i=0
        for bar, treatment in zip(bars, df['treatment_name']):
                height = bar.get_height()
                plt.annotate(treatment,
                xy=(bar.get_x() + bar.get_width() / 2, height),
                xytext=(0, 3),
                textcoords="offset points",
                ha='center', va='bottom', fontsize=9)
   
                #Matplotlib optimise automatiquement l’espacement autour des éléments du graphique, pour que tout rentre proprement dans la figure
        plt.tight_layout()
        plt.gca().spines['top'].set_visible(False)
        plt.gca().spines['right'].set_visible(False)
        #plt.show()
        # sauvgarde le fich
        # Supprimer les  bordures (top, right)
       
        if len(sys.argv) > 1 and sys.argv[1]=="1":
                plt.show()
        else:
                # Crée le dossier s'il n'existe pas
                os.makedirs("images", exist_ok=True)
                # Sauvegarder l'image dans le dossier 'images'
                plt.savefig("images/traitementperdiagnoses.png")

        
except mysql.connector.Error as err:
        print(f"Erreur : {err}")


finally:
        conn.close()