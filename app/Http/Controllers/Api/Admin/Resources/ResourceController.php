<?php

namespace App\Http\Controllers\Api\Admin\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class ResourceController extends Controller
{
    /** @var class-string<Model> */
    protected string $model;

    public function index(): array
    {
        $model = $this->model;

        return ['data' => $model::query()->latest('id')->paginate(15)];
    }

    public function store(Request $request): JsonResponse
    {
        $model = $this->model;
        $record = $model::query()->create($request->validate($this->rules()));

        return response()->json(['data' => $record], 201);
    }

    public function show(Request $request): array
    {
        return ['data' => $this->findRecord($request)];
    }

    public function update(Request $request): array
    {
        $record = $this->findRecord($request);
        $record->update($request->validate($this->rules(true)));

        return ['data' => $record->refresh()];
    }

    public function destroy(Request $request): JsonResponse
    {
        $this->findRecord($request)->delete();

        return response()->json(['message' => 'Content deleted.']);
    }

    /** @return array<string, array<int, string>> */
    abstract protected function rules(bool $partial = false): array;

    private function findRecord(Request $request): Model
    {
        $id = collect($request->route()->parameters())->last();
        $model = $this->model;

        return $model::query()->findOrFail($id);
    }
}
