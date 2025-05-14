import matplotlib.pyplot as plt
import numpy as np
import sys
from scipy.interpolate import make_interp_spline
import mysql.connector
from dotenv import load_dotenv
import os
from pathlib import Path

if not load_dotenv(dotenv_path = Path('C:/xampp/htdocs/managementdesease/Login/.env')):
        print( "Erreur : Impossible de charger le fichier .env")
           
try:
        mydb = mysql.connector.connect(
            host=os.getenv('localhost'),
            user=os.getenv('DB_user_name'),
            password=os.getenv('DB_PASSWORD'),
            database="diseasemanagement"
        )

        sql="select acess_date,count(*) from acess_dates group by acess_date order by acess_date desc limit 5;"
        mycursor=mydb.cursor()
        mycursor.execute(sql)
        # Récupérer tous les résultats
        results = mycursor.fetchall()

                # Extraire chaque colonne séparément
        dates = [row[0] for row in results]  # Première colonne (acess_date)
        nbacess = [row[1] for row in results]  # Deuxième colonne (COUNT)
        dates.reverse()
        nbacess.reverse()
        #plt.figure(figsize=(5, 4))  # ✅  on définit la taille de la figure
        x_num= np.arange(len(dates))
        y=np.array(nbacess)

        plt.gca().set_facecolor('white')  # Fond très clair
        plt.rcParams['font.family'] = 'DejaVu Sans'  # Police moderne

        plt.xticks(ticks=x_num, labels=dates)

        # Création d'une spline pour lisser la courbe
        x_smooth = np.linspace(x_num.min(), x_num.max(), 200)  # plus de points pour une courbe fluide
        spl = make_interp_spline(x_num, y, k=3)  # k=3 pour une courbe douce (cubic spline)
        y_smooth = spl(x_smooth)
        plt.scatter(x_num, y)  # Afficher les vrais points si tu veux
        plt.plot(x_smooth,y_smooth)
        plt.fill_between(x_smooth, y_smooth,  alpha=0.15,color="#44b0f8")  # Remplissage doux
        plt.xlabel('Month')
        plt.ylabel('Number of diagnosis')
        plt.tight_layout()

        # Supprimer les  bordures (top, right)
        plt.gca().spines['top'].set_visible(False)
        plt.gca().spines['right'].set_visible(False)

        # Supprimer l'interaction si possible
        plt.gca().format_coord = lambda x, y:""  # Vide les coordonnées affichées

except mysql.connector.Error as err:
        print(f"Erreur : {err}")

if len(sys.argv) > 1 and sys.argv[1]=="1":
    plt.show()
else:
    # Crée le dossier s'il n'existe pas
    os.makedirs("images", exist_ok=True)
     # Sauvegarder l'image dans le dossier 'images'
    plt.savefig("images/nbConnexion.png")
    

