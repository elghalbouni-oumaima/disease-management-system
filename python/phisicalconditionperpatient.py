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

        query=""" select physical_condition,count(*) as nb from patient group by physical_condition; """
        df=pd.read_sql_query(query,conn)
        print(df)
        color=['#fff59d','#a1887f','#00bcd4','#ce93d8']
        plt.figure(figsize=(10, 6))
        plt.pie(df['nb'],labels=df['physical_condition'],autopct='%1.1f%%', startangle=140, colors=color)
        #plt.title('Distribution of Patient by Physical Condition', fontsize=14, weight='bold')
        

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
                plt.savefig("images/phisicalconditionperpatient.png")    
except mysql.connector.Error as err:
        print(f"Erreur : {err}")