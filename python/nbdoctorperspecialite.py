import matplotlib.pyplot as plt
import mysql.connector
import pandas as pd
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

        query=""" select specialization,count(*) as nb from doctor WHERE specialization IS NOT NULL group by specialization; """
        df=pd.read_sql_query(query,conn)
        print(df)
        #plt.figure(figsize=(20, 10))
        
        #plt.pie(df['nb'],labels=df['specialization'],autopct='%1.1f%%',  fontsize=17, startangle=140, colors=plt.cm.Blues(range(50, 250, 40)))
        wedges, texts, autotexts = plt.pie(
        df['nb'],
        labels=df['specialization'],
        autopct='%1.1f%%',
        startangle=140,
        colors=plt.cm.Blues(range(50, 250, 40))
        )

        # Modifier la taille des labels
        for text in texts:
                text.set_fontsize(14)

        # Modifier la taille des pourcentages
        for autotext in autotexts:
                autotext.set_fontsize(15)
                #autotext.set_color("white")  # facultatif : meilleure visibilité
        #plt.title('Distribution of Doctors by Specialization', fontsize=17, weight='bold')

        # Affichage
        plt.axis('equal')  # Pour que le cercle soit rond
        # Supprimer les  bordures (top, right)
        # plt.gca().spines['top'].set_visible(False)
        # plt.gca().spines['right'].set_visible(False)
        #plt.get_current_fig_manager().full_screen_toggle()
        #plt.show()
        if len(sys.argv) > 1 and sys.argv[1]=="1":
                plt.show()
        else:
                # Crée le dossier s'il n'existe pas
                os.makedirs("images", exist_ok=True)
                # Sauvegarder l'image dans le dossier 'images'
                #plt.savefig("images/nbdoctorperspecialite.png") 
                plt.savefig("images/nbdoctorperspecialite.png", dpi=300, bbox_inches='tight')
   
except mysql.connector.Error as err:
        print(f"Erreur : {err}")