<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class ctrlProduct extends Controller
{
    public function index(Request $request): JsonResponse|RedirectResponse
    {
        $productos = Product::with('category')->orderByDesc('id')->get();

        return $this->respond($request, $productos);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'not_regex:/\d/'],
            'description' => ['nullable', 'string', 'not_regex:/\d/'],
            'descriptionLong' => ['nullable', 'string', 'not_regex:/\d/'],
            'price' => ['required', 'numeric', 'min:0'],
            'idcategory' => ['required', 'integer', 'exists:categories,id'],
        ], [
            'name.not_regex' => 'El nombre no debe contener numeros.',
            'description.not_regex' => 'La descripcion no debe contener numeros.',
            'descriptionLong.not_regex' => 'La descripcion larga no debe contener numeros.',
        ]);

        $producto = Product::create($data);

        return $this->respond(
            $request,
            $producto,
            'Producto creado correctamente.',
            201,
            '/productos'
        );
    }

    public function show(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $producto = Product::with('category')->findOrFail($id);

        return $this->respond($request, $producto);
    }

    public function update(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $producto = Product::findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'not_regex:/\d/'],
            'description' => ['nullable', 'string', 'not_regex:/\d/'],
            'descriptionLong' => ['nullable', 'string', 'not_regex:/\d/'],
            'price' => ['required', 'numeric', 'min:0'],
            'idcategory' => ['required', 'integer', 'exists:categories,id'],
        ], [
            'name.not_regex' => 'El nombre no debe contener numeros.',
            'description.not_regex' => 'La descripcion no debe contener numeros.',
            'descriptionLong.not_regex' => 'La descripcion larga no debe contener numeros.',
        ]);

        $producto->update($data);

        return $this->respond(
            $request,
            $producto,
            'Producto actualizado correctamente.',
            200,
            '/productos'
        );
    }

    public function destroy(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $producto = Product::findOrFail($id);
        $producto->delete();

        return $this->respond(
            $request,
            null,
            'Producto eliminado correctamente.',
            200,
            '/productos'
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

        return redirect($redirectTo ?? '/productos')->with('success', $message);
    }
}
