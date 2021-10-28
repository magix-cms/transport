# Transport
Plugin transport for Magix CMS 3

Ajouter des villes pour le transport dans votre site.

## Installation
* Décompresser l'archive dans le dossier "plugins" de magix cms
* Connectez-vous dans l'administration de votre site internet
* Cliquer sur l'onglet plugins du menu déroulant pour sélectionner transport.
* Une fois dans le plugin, laisser faire l'auto installation
* Il ne reste que la configuration du plugin pour correspondre avec vos données.

### Infos
Ce plugin est utilisé pour compléter d'autres plugins comme "cartpay" ou tout autre système ayant besoin d'attributs.

### Exemple d'utilisation
```smarty
{transport_data}
    <pre>{$transport|print_r}</pre>
````
