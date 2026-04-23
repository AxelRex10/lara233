<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\QueryException;

class ctrlCategory extends Controller
{
    public function index(Request $request): JsonResponse|RedirectResponse
    {
        $categorias = category::orderByDesc('id')->get();

        return $this->respond($request, $categorias);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'not_regex:/\d/'],
            'description' => ['nullable', 'string', 'not_regex:/\d/'],
        ], [
            'name.not_regex' => 'El nombre no debe contener numeros.',
            'description.not_regex' => 'La descripcion no debe contener numeros.',
        ]);

        $categoria = category::create($data);

        return $this->respond(
            $request,
            $categoria,
            'Categoria creada correctamente.',
            201,
            '/categorias'
        );
    }

    public function show(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $categoria = category::findOrFail($id);

        return $this->respond($request, $categoria);
    }

    public function update(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $categoria = category::findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'not_regex:/\d/'],
            'description' => ['nullable', 'string', 'not_regex:/\d/'],
        ], [
            'name.not_regex' => 'El nombre no debe contener numeros.',
            'description.not_regex' => 'La descripcion no debe contener numeros.',
        ]);

        $categoria->update($data);

        return $this->respond(
            $request,
            $categoria,
            'Categoria actualizada correctamente.',
            200,
            '/categorias'
        );
    }

    public function destroy(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $categoria = category::findOrFail($id);

        try {
            $categoria->delete();
        } catch (QueryException $e) {
            if ((int) $e->getCode() === 23000) {
                if ($request->is('api/*') || $request->wantsJson() || $request->expectsJson()) {
                    return response()->json([
                        'message' => 'No se puede eliminar la categoria porque tiene productos relacionados.',
                    ], 409);
                }

                return redirect('/categorias')->with('error', 'No se puede eliminar la categoria porque tiene productos relacionados.');
            }

            throw $e;
        }

        return $this->respond(
            $request,
            null,
            'Categoria eliminada correctamente.',
            200,
            '/categorias'
        );
    }

    private function respond(
        Request $request,
        mixed $data,
        string $message = 'Operacion realizada correctamente.',
        int $status = 200,
        ?string $redirectTo = null
    ): JsonResponse|RedirectResponse {
        if ($request->is('api/*') || $request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'data' => $data,
            ], $status);
        }

        return redirect($redirectTo ?? '/categorias')->with('success', $message);
    }
}
