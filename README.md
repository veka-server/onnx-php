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

| Modèles                                         | Description                                      | Statut | Exemple |
|  :---                                           |   :---                                     |   :---:   |   :---:   |
| [suko / nsfw](https://huggingface.co/suko/nsfw) | classification : Naked , SAFE | ✅      | [wiki](https://github.com/veka-server/onnx-php/wiki/suko-nsfw) |
| [wd14](https://huggingface.co/SmilingWolf/wd-convnext-tagger-v3)                                   | classification : tout les tags de danbooru                                        | ✅     | [wiki](https://github.com/veka-server/onnx-php/wiki/wd14)  |
| [NUDENET classifier (classifier_model.onnx)](https://github.com/notAI-tech/NudeNet)      | classification : unsafe , safe      | ✅      | [wiki](https://github.com/veka-server/onnx-php/wiki/nudenet%E2%80%90classifier) |
| [NSFW Detection](https://github.com/iola1999/nsfw-detect-onnx)      | classification : drawing, hentai, neutral, porn, sexy      | ✅      | [wiki](https://github.com/veka-server/onnx-php/wiki/nsfw%E2%80%90detection) |

#### Détection d'objets

| Modèles                                          | Description                                      | Statut | Exemple |
|  :---                                           |     :---                       |   :---:   |   :---:   |
| YOLOV10                                         |  detection de 80 objets [voir la liste](https://huggingface.co/onnx-community/yolov10m/blob/main/config.json)                                              | ✅      |  [wiki](https://github.com/veka-server/onnx-php/wiki/yolov10)   |
| YOLOV5 Face                                     | ...                                              | ☐      |     |
| [NUDENET v3 detector (640m.onnx) ](https://github.com/notAI-tech/NudeNet)      | detection : FEMALE_GENITALIA_COVERED, FACE_FEMALE, BUTTOCKS_EXPOSED, FEMALE_BREAST_EXPOSED, FEMALE_GENITALIA_EXPOSED, MALE_BREAST_EXPOSED, ANUS_EXPOSED, FEET_EXPOSED, BELLY_COVERED, FEET_COVERED, ARMPITS_COVERED, ARMPITS_EXPOSED, FACE_MALE, BELLY_EXPOSED, MALE_GENITALIA_EXPOSED, ANUS_COVERED, FEMALE_BREAST_COVERED, BUTTOCKS_COVERED                             | ✅      | [wiki](https://github.com/veka-server/onnx-php/wiki/nudenet%E2%80%90detection) |

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

