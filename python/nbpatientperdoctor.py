

import matplotlib.pyplot as plt
import matplotlib.cm as cm
import mysql.connector
import pandas as pd
import numpy as np
import os
import sys
from dotenv import load_dotenv
from pathlib import Path
from datetime import datetime


def generate_axs(conn,current_year,ax1):
        query = f"""
        SELECT d.full_name, COUNT(p.id) AS nb 
        FROM doctor d, patient p
        WHERE d.username = p.doctor_id
        and year(p.registration_day) ={current_year}
        GROUP BY d.full_name
        ORDER BY nb DESC 
        LIMIT 20
    """
        df = pd.read_sql(query, conn)
        bar_width = 0.5
        index = np.arange(len(df['nb']))
        
        # # Choisir un colormap (ex: 'viridis', 'plasma', 'cool', 'tab20', etc.)
        # cmap = cm.get_cmap('cool', len(df))  # ou 'plasma', 'tab20', 'coolwarm'...
        # # Générer une couleur différente pour chaque barre
        # colors = [cmap(i) for i in range(len(df))]

        cmap = plt.colormaps['cool']  # ou 'plasma', 'viridis', etc.
        colors = [cmap(i / len(df)) for i in range(len(df))]
        bars = ax1.bar(index, df['nb'], bar_width, label='Patients', color=colors)
        #current_year = datetime.now().year
        ax1.set_title(f'Top 20 Doctors by Number of Patients in {current_year} ', fontsize=14, fontweight='bold')
        ax1.set_xlabel('Doctors', fontsize=12)
        ax1.set_ylabel('Number of Patients', fontsize=12)
        ax1.set_xticks(index, df['full_name'], rotation=-45, ha='left', fontsize=11)

        # Ajouter les valeurs sur les barres
        for bar in bars:
            height = bar.get_height()
            ax1.annotate(f'{int(height)}',
                        xy=(bar.get_x() + bar.get_width() / 2, height),
                        xytext=(0, 3),
                        textcoords="offset points",
                        ha='center', va='bottom', fontsize=9)
        
       
if not load_dotenv(dotenv_path = Path('C:/xampp/htdocs/managementdesease/Login/.env')):
        print( "Erreur : Impossible de charger le fichier .env")
           
try:
        conn = mysql.connector.connect(
            host=os.getenv('localhost'),
            user=os.getenv('DB_user_name'),
            password=os.getenv('DB_PASSWORD'),
            database="diseasemanagement"
        )
    
        fig, (ax2, ax1) = plt.subplots(1, 2, figsize=(12, 5))  # 1 ligne, 2 colonnes
        current_year = datetime.now().year
        generate_axs(conn,current_year - 1,ax2)
        generate_axs(conn,current_year,ax1)



        
        # # Supprimer les bordures
        # plt.gca().spines['top'].set_visible(False)
        # plt.gca().spines['right'].set_visible(False)

        plt.tight_layout()
        
        if len(sys.argv) > 1 and sys.argv[1]=="1":
                plt.show()
        else:
                # Crée le dossier s'il n'existe pas
                os.makedirs("images", exist_ok=True)
                # Sauvegarder l'image dans le dossier 'images'
                plt.savefig("images/nbpatientperdoctor.png")

        
except mysql.connector.Error as err:
        print(f"Erreur : {err}")


finally:
        conn.close()