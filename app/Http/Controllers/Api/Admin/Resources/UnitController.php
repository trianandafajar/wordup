<?php

namespace App\Http\Controllers\Api\Admin\Resources;

use App\Models\Unit;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UnitController extends ResourceController
{
    protected string $model = Unit::class;

    #[Group('Admin Units')]
    public function index(): array
    {
        return parent::index();
    }

    #[Group('Admin Units')]
    public function store(Request $request): JsonResponse
    {
        return parent::store($request);
    }

    #[Group('Admin Units')]
    public function show(Request $request): array
    {
        return parent::show($request);
    }

    #[Group('Admin Units')]
    public function update(Request $request): array
    {
        return parent::update($request);
    }

    #[Group('Admin Units')]
    public function destroy(Request $request): JsonResponse
    {
        return parent::destroy($request);
    }

    protected function rules(bool $partial = false): array
    {
        $required = $partial ? 'sometimes|required' : 'required';

        return [
            'course_id' => [$required, 'integer', 'exists:courses,id'],
            'title' => [$required, 'string', 'max:255'],
            'order' => [$required, 'integer', 'min:0'],
        ];
    }
}
