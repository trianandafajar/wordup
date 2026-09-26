<?php

namespace App\Http\Controllers\Api\Admin\Resources;

use App\Models\Lesson;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonController extends ResourceController
{
    protected string $model = Lesson::class;

    #[Group('Admin Lessons')]
    public function index(): array
    {
        return parent::index();
    }

    #[Group('Admin Lessons')]
    public function store(Request $request): JsonResponse
    {
        return parent::store($request);
    }

    #[Group('Admin Lessons')]
    public function show(Request $request): array
    {
        return parent::show($request);
    }

    #[Group('Admin Lessons')]
    public function update(Request $request): array
    {
        return parent::update($request);
    }

    #[Group('Admin Lessons')]
    public function destroy(Request $request): JsonResponse
    {
        return parent::destroy($request);
    }

    protected function rules(bool $partial = false): array
    {
        $required = $partial ? 'sometimes|required' : 'required';

        return [
            'unit_id' => [$required, 'integer', 'exists:units,id'],
            'title' => [$required, 'string', 'max:255'],
            'explanation' => ['sometimes', 'nullable', 'string'],
            'order' => [$required, 'integer', 'min:0'],
            'xp_reward' => [$required, 'integer', 'min:0'],
            'type' => [$required, 'in:reading,listening'],
        ];
    }
}
