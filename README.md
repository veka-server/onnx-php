# ONNX-PHP

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
|-------------------------|------------------------------------------------------------------------------------------------------------------------------------------------------|--------|
| Classification d'images | Attribuer une ou plusieurs étiquettes à une image, en identifiant à quelle catégorie l'image appartient parmi un ensemble de catégories prédéfinies. | ✅      |
| Détection d'objets      | Localiser et identifier des objets spécifiques dans une image, en dessinant des boîtes englobantes autour des objets détectés et en les étiquetant avec leurs catégories correspondantes.  | ✅      |

#### Traitement du Langage Naturel

| Tâches                    | Description                                                                                                  | Statut |
|-------------------------|--------------------------------------------------------------------------------------------------------------|--------|
| Génération de texte | Produire un texte cohérent et fluide en réponse à une entrée donnée, comme une phrase ou un contexte.        | ✅     |

## Models testé
#### Classification d'images

| Models                                          | Description                                      | Statut |
|-------------------------------------------------|--------------------------------------------------|--------|
| [suko / nsfw](https://huggingface.co/suko/nsfw) | Permet de classer une image entre Naked et SAFE. | ✅      |
| Naked                                           | ...                                              | ☐      |
| NUDENET 1                                       | ...                                              | ☐      |
| NSFW                                         | ...                                              | ☐      |

#### Détection d'objets

| Models                                          | Description                                      | Statut |
|-------------------------------------------------|--------------------------------------------------|--------|
| YOLOV10                                         | ...                                              | ☐      |
| YOLOV5 Face                                     | ...                                              | ☐      |
| NUDENET 2                                       | ...                                              | ☐      |
| NUDENET 3                                       | ...                                              | ☐      |

## Exemple d'utilisation
```php
require_once(__DIR__.'/../vendor/autoload.php');

/** Set the directory where the library will be downloaded, if it not set then it will be stored inside vendor directory */
Onnx\Library::setFolder(__DIR__.'/../');

/** Download the library if not found */
Onnx\Library::install();

/** Instanciate Vision */
$ia = new Onnx\Task\Vision(config:[
    'tags' => [ 0 => "Naked", 1 => "Safe"]
    ,'rescale_factor' => 0.00392156862745098
    ,'format' => 'rgb'
    ,'height' => 224
    ,'width' => 224
    ,'shape' => 'bhwc'  // batch channel height width
    ,'modelNameOrPath' => __DIR__.'/../models/model_suko_nsfw.onnx' // https://huggingface.co/suko/nsfw
]);

/** Load models */
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
Cette librairie est dérivé des travaux de [onnxruntime-php](https://github.com/ankane/onnxruntime-php) et [transformers-php](https://github.com/CodeWithKyrian/transformers-php)

