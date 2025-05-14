import matplotlib.pyplot as plt
import numpy as np

# Données
x = np.arange(5)
y = [2, 4, 1, 8, 7]

# Créer la figure et les axes
fig, ax = plt.subplots(figsize=(6, 4))
ax.plot(x, y, marker='o', color='blue')

# Fonction pour afficher le message quand un point est cliqué
def onpick(event):
    # Obtenir l'indice du point cliqué
    ind = event.ind
    x_click = x[ind][0]  # Coordonnée X du point cliqué
    y_click = y[ind][0]  # Coordonnée Y du point cliqué
    
    # Afficher un message à côté du point cliqué
    ax.text(x_click, y_click, f'({x_click}, {y_click})', fontsize=12, color='red', ha='left', va='bottom')
    
    # Redessiner le graphique avec le texte
    plt.draw()

# Activer la fonctionnalité de sélection des points
fig.canvas.mpl_connect('pick_event', onpick)

# Activer le mode de "pick" pour les points (sommets)
ax.plot(x, y, marker='o', color='blue', picker=True)

# Afficher le graphique
plt.title("Cliquez sur un sommet pour afficher un message")
plt.tight_layout()
plt.show()
