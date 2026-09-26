<?php

namespace App\Http\Controllers\Api\Admin\Resources;

use App\Models\Course;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends ResourceController
{
    protected string $model = Course::class;

    #[Group('Admin Courses')]
    public function index(): array
    {
        return parent::index();
    }

    #[Group('Admin Courses')]
    public function store(Request $request): JsonResponse
    {
        return parent::store($request);
    }

    #[Group('Admin Courses')]
    public function show(Request $request): array
    {
        return parent::show($request);
    }

    #[Group('Admin Courses')]
    public function update(Request $request): array
    {
        return parent::update($request);
    }

    #[Group('Admin Courses')]
    public function destroy(Request $request): JsonResponse
    {
        return parent::destroy($request);
    }

    protected function rules(bool $partial = false): array
    {
        $required = $partial ? 'sometimes|required' : 'required';

        return [
            'title' => [$required, 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'language_target' => [$required, 'string', 'max:50'],
            'level' => [$required, 'string', 'max:100'],
            'is_active' => [$required, 'boolean'],
        ];
    }
}
