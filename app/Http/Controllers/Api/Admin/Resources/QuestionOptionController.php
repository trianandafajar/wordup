<?php

namespace App\Http\Controllers\Api\Admin\Resources;

use App\Models\QuestionOption;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestionOptionController extends ResourceController
{
    protected string $model = QuestionOption::class;

    #[Group('Admin Question Options')]
    public function index(): array
    {
        return parent::index();
    }

    #[Group('Admin Question Options')]
    public function store(Request $request): JsonResponse
    {
        return parent::store($request);
    }

    #[Group('Admin Question Options')]
    public function show(Request $request): array
    {
        return parent::show($request);
    }

    #[Group('Admin Question Options')]
    public function update(Request $request): array
    {
        return parent::update($request);
    }

    #[Group('Admin Question Options')]
    public function destroy(Request $request): JsonResponse
    {
        return parent::destroy($request);
    }

    protected function rules(bool $partial = false): array
    {
        $required = $partial ? 'sometimes|required' : 'required';

        return [
            'question_id' => [$required, 'integer', 'exists:questions,id'],
            'option_text' => [$required, 'string'],
            'is_correct' => [$required, 'boolean'],
        ];
    }
}
