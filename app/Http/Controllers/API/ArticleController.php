<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    // Récupérer tous les articles
    public function index()
    {
        return response()->json(Article::all());
    }

    // Ajouter un nouvel article
    public function store(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'nom_marque' => 'required|string|max:255',
            'nom_famille' => 'required|string|max:255',
            'modele' => 'required|string|max:255',
            'description' => 'nullable|string',
            'nom_couleur' => 'nullable|string|max:255',
            'prix_public' => 'required|numeric',
            'prix_achat' => 'required|numeric',
            'img' => 'nullable|string|max:255',
            'id_famille' => 'required|integer', // Validation pour id_famille
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Création de l'article
        try {
            $article = Article::create($request->all());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la création de l\'article. ' . $e->getMessage()], 500);
        }

        return response()->json($article, 201);
    }

    // Méthode pour mettre à jour un article (si nécessaire)
    public function update(Request $request, $id)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'nom_marque' => 'sometimes|required|string|max:255',
            'nom_famille' => 'sometimes|required|string|max:255',
            'modele' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'nom_couleur' => 'sometimes|nullable|string|max:255',
            'prix_public' => 'sometimes|required|numeric',
            'prix_achat' => 'sometimes|required|numeric',
            'img' => 'sometimes|nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        $article = Article::find($id);
        if (!$article) {
            return response()->json(['error' => 'Article non trouvé.'], 404);
        }

        // Mise à jour de l'article
        try {
            $article->update($request->all());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la mise à jour de l\'article.'], 500);
        }

        return response()->json($article, 200);
    }
}
