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

        query=" select blood_type,count(*) as nb from patient  where blood_type !='none' group by blood_type"
        df=pd.read_sql_query(query,conn)
        print(df)
        # Couleurs pastel convenables
        colors = ['#ef5350', '#ef9a9a', '#b39ddb', 'red', '#4dd0e1',
                '#C2C2F0', '#FFB6C1', '#D3D3D3', '#B0E0E6', '#f44336']

        # Graphe donut
        plt.figure(figsize=(9, 6))
        wedges, texts, autotexts = plt.pie(
        df['nb'],
        labels=df['blood_type'],
        autopct='%1.1f%%',
        startangle=140,
        colors=plt.cm.Reds(range(50, 250, 40)),
        )
        #plt.figure(figsize=(10, 6))
        #plt.pie(df['nb'],labels=df['blood_type'],autopct='%1.1f%%', startangle=140, colors=plt.cm.Reds(range(50, 200, 40)))
        #plt.title('Répartition des diagnostics', fontsize=14, weight='bold')

        # Affichage
        plt.axis('equal')  # Pour que le cercle soit rond
        # Supprimer les  bordures (top, right)
        plt.gca().spines['top'].set_visible(False)
        plt.gca().spines['right'].set_visible(False)
        #plt.show()
        if len(sys.argv) > 1 and sys.argv[1]=="1":
                plt.show()
        else:
                # Crée le dossier s'il n'existe pas
                os.makedirs("images", exist_ok=True)
                # Sauvegarder l'image dans le dossier 'images'
                plt.savefig("images/nbpatientperbloodtype.png",dpi=300, bbox_inches='tight')
except mysql.connector.Error as err:
        print(f"Erreur : {err}")