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
        query="""
        select  
            sum(case when age<18 then 1 else 0 end) as age1,
            sum(case when age>=18 and age<35 then 1 else 0 end) as age2,
            sum(case when age>=35 and age<60 then 1 else 0 end) as age3,
            sum(case when age>=60  then 1 else 0 end) as age4
            from (select TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) AS age from patient) as agespatent
            """
        df=pd.read_sql(query,conn)
        
        values = df.iloc[0].tolist()
        labels = ["0-18", "18–34", "35–59", "60+"]
        
        # Largeur des barres
        bar_width = 0.35
        # Création du graphique
        plt.figure(figsize=(10, 6))
        index = np.arange(len(values))
        bars=plt.bar(index, values, bar_width, label='Patient', color='#bb8fce')
        for bar in bars:
            height = bar.get_height()
            plt.annotate(f'{int(height)}',
                        xy=(bar.get_x() + bar.get_width() / 2, height),
                        xytext=(0, 3),  # décalage vertical
                        textcoords="offset points",
                        ha='center', va='bottom', fontsize=9)
        # Personnalisation
        #plt.title('Distribution of Patients by Age Group')
        plt.xlabel('Age Group')
        plt.ylabel('Number of Patients')
        plt.legend()

        plt.xticks(index, labels)
        #Matplotlib optimise automatiquement l’espacement autour des éléments du graphique, pour que tout rentre proprement dans la figure
        plt.tight_layout()
        #plt.show()
        # sauvgarde le fich
        # Supprimer les  bordures (top, right)
        plt.gca().spines['top'].set_visible(False)
        plt.gca().spines['right'].set_visible(False)
        if len(sys.argv) > 1 and sys.argv[1]=="1":
                plt.show()
        else:
                # Crée le dossier s'il n'existe pas
                os.makedirs("images", exist_ok=True)
                # Sauvegarder l'image dans le dossier 'images'
                plt.savefig("images/nbpatientperages.png",dpi=300, bbox_inches='tight')

        
except mysql.connector.Error as err:
        print(f"Erreur : {err}")


finally:
        conn.close()