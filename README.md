<h1 align="center">ONNX-PHP</h1>

<p align="center">
<a href="https://packagist.org/packages/codewithkyrian/transformers"><img src="https://img.shields.io/packagist/dt/veka-server/onnx-php" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/codewithkyrian/transformers"><img src="https://img.shields.io/packagist/v/veka-server/onnx-php" alt="Latest Stable Version"></a>
<a href="https://github.com/CodeWithKyrian/transformers-php/blob/main/LICENSE"><img src="https://img.shields.io/github/license/veka-server/onnx-php" alt="License"></a>
<a href="https://github.com/codewithkyrian/transformers-php"><img src="https://img.shields.io/github/repo-size/veka-server/onnx-php" alt="Documentation"></a>
</p>

**ONNX-PHP** est une bibliothèque PHP permettant de charger et d'exécuter des modèles IA au format ONNX.
Dans cet objectif, la librairie utilise `FFI` pour interagir directement avec `onnxruntime`. 

Cette bibliothèque offre une API simple et flexible pour intégrer des modèles IA dans vos applications PHP.

## Installation

Vous pouvez installer cette bibliothèque via [Composer](https://getcomposer.org/).

```bash
composer require veka-server/onnx-php
```

## Prérequis PHP

- PHP >= 8.1
- FFI 
- GD

## Tâches Disponibles
#### Vision

| Tâches                  | Description                                                                                                                                          | Statut |
|  :---                                           |   :---                                     |   :---:   |
| Classification d'images | Attribuer une ou plusieurs étiquettes à une image, en identifiant à quelle catégorie l'image appartient parmi un ensemble de catégories prédéfinies. | ✅      |
| Détection d'objets      | Localiser et identifier des objets spécifiques dans une image, en dessinant des boîtes englobantes autour des objets détectés et en les étiquetant avec leurs catégories correspondantes.  | ✅      |

#### Traitement du Langage Naturel

| Tâches                    | Description                                                                                                  | Statut |
|  :---                                           |   :---                                     |   :---:   |
| Génération de texte | Produire un texte cohérent et fluide en réponse à une entrée donnée, comme une phrase ou un contexte.        | ✅     |

## Modèles testé
#### Classification d'images

| Modèles                                          | Description                                      | Statut |
|  :---                                           |   :---                                     |   :---:   |
| [suko / nsfw](https://huggingface.co/suko/nsfw) | Permet de classer une image entre Naked et SAFE. | ✅      |
| Naked                                           | ...                                              | ☐      |
| NUDENET 1                                       | ...                                              | ☐      |
| NSFW                                         | ...                                              | ☐      |

#### Détection d'objets

| Modèles                                          | Description                                      | Statut |
|  :---                                           |   :---                                     |   :---:   |
| YOLOV10                                         | ...                                              | ☐      |
| YOLOV5 Face                                     | ...                                              | ☐      |
| NUDENET 2                                       | ...                                              | ☐      |
| NUDENET 3                                       | ...                                              | ☐      |

## Exemple d'utilisation
```php
require_once(__DIR__.'/../vendor/autoload.php');

/** Définir le répertoire où la bibliothèque sera téléchargée. Si ce n'est pas défini, elle sera stockée dans le répertoire vendor */
Onnx\Library::setFolder(__DIR__.'/../');

/** Télécharger la bibliothèque si elle n'est pas trouvée */
Onnx\Library::install();

/** Instancier Vision */
$ia = new Onnx\Task\Vision(config:[
    // Liste des étiquettes pour les classifications. Les indices correspondent aux identifiants des classes.
    'tags' => [ 
        0 => "Naked", 
        1 => "Safe"  
    ],
    // Facteur de mise à l'échelle des valeurs des pixels de l'image. 
    // Ce facteur est utilisé pour normaliser les valeurs des pixels, souvent de 0 à 1.
    'rescale_factor' => 0.00392156862745098, // 1/255 pour convertir les valeurs de pixels de [0, 255] à [0, 1]
    
    // Format des canaux de couleur de l'image. 'rgb' signifie que l'image est en format Red, Green, Blue.
    'format' => 'rgb',
    
    // Hauteur de l'image en pixels attendu par le modèle. Les images seront automatiquement redimensionné à cette dimmension.
    'height' => 224,
    
    // Largeur de l'image en pixels attendu par le modèle. Les images seront automatiquement redimensionné à cette dimmension.
    'width' => 224,
    
    // La forme des données d'entrée pour le modèle. 'bhwc' signifie que les données sont en format :
    // batch (nombre d'images), height (hauteur des images), width (largeur des images), channel (nombre de canaux de couleur).
    'shape' => 'bhwc', 
    
    // Chemin vers le modèle ONNX. Il doit pointer vers le fichier du modèle pré-entraîné.
    'modelNameOrPath' => __DIR__.'/../models/model_suko_nsfw.onnx' 
]);

/** Charger les modèles */
$ia->loadModel();

/** Analyse de l'image */
$tags = $ia->getTags($img);

var_dump($tags);

```

## Contribution
Les contributions sont les bienvenues !

Si vous avez des suggestions, trouvez des bugs, ou voulez ajouter des fonctionnalités ou des models, n'hésitez pas à ouvrir une issue ou une pull request.

## License
Ce projet est sous licence MIT. Voir le fichier LICENSE pour plus de détails.

## Remerciements
Un grand merci à la communauté ONNX et à tous les contributeurs de onnxruntime pour leur travail exceptionnel.
Cette librairie est dérivé des travaux de :
- [onnxruntime-php](https://github.com/ankane/onnxruntime-php)
- [transformers-php](https://github.com/CodeWithKyrian/transformers-php)
- [onnxruntime](https://github.com/Microsoft/onnxruntime)

