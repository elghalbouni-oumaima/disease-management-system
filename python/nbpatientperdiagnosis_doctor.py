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
        query=""" select d.diagnosis_name,count(p.id) as nb from diagnosis d, patient p
                where d.patient_id=p.id
                and p.doctor_id=%s
                group by d.diagnosis_name
                order by nb desc limit 7"""
        df=pd.read_sql(query,conn,params=[doctor_id])
        
        
        # Largeur des barres
        bar_width = 0.5
        # Création du graphique
        plt.figure(figsize=(10, 6))
        index = np.arange(len(df))
        bars=plt.bar(index, df['nb'], bar_width, label='Patient', color='#4dd0e1')

        # Personnalisation
        #plt.title(' Most Common Diagnoses Among Patients',fontsize=15,fontweight='bold')
        plt.xlabel('Popular Diagnoses',fontsize=13)
        plt.ylabel('Number of Patients',fontsize=13)
        plt.legend()

        plt.xticks(index, df['diagnosis_name'],fontsize=12)
        # Ajouter les valeurs au-dessus des barres
        for bar in bars:
            height = bar.get_height()
            plt.annotate(f'{int(height)}',
                        xy=(bar.get_x() + bar.get_width() / 2, height),
                        xytext=(0, 3),  # décalage vertical
                        textcoords="offset points",
                        ha='center', va='bottom', fontsize=9)
                #Matplotlib optimise automatiquement l’espacement autour des éléments du graphique, pour que tout rentre proprement dans la figure
        plt.tight_layout()
        #plt.show()
        # sauvgarde le fich
        # Supprimer les  bordures (top, right)
        plt.gca().spines['top'].set_visible(False)
        plt.gca().spines['right'].set_visible(False)
        if len(sys.argv) > 2 and sys.argv[2]=="1":
                plt.show()
        else:
                # Crée le dossier s'il n'existe pas
                os.makedirs("images", exist_ok=True)
                # Sauvegarder l'image dans le dossier 'images'
                plt.savefig("images/nbpatientperdiagnosis_doctor.png",dpi=300, bbox_inches='tight')

        
except mysql.connector.Error as err:
        print(f"Erreur : {err}")


finally:
        conn.close()