<?php

namespace App\Http\Controllers\Api\Admin\Resources;

use App\Enums\QuestionDifficultyEnum;
use App\Enums\QuestionTypeEnum;
use App\Models\Question;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestionController extends ResourceController
{
    protected string $model = Question::class;

    #[Group('Admin Questions')]
    public function index(): array
    {
        return parent::index();
    }

    #[Group('Admin Questions')]
    public function store(Request $request): JsonResponse
    {
        return parent::store($request);
    }

    #[Group('Admin Questions')]
    public function show(Request $request): array
    {
        return parent::show($request);
    }

    #[Group('Admin Questions')]
    public function update(Request $request): array
    {
        return parent::update($request);
    }

    #[Group('Admin Questions')]
    public function destroy(Request $request): JsonResponse
    {
        return parent::destroy($request);
    }

    protected function rules(bool $partial = false): array
    {
        $required = $partial ? 'sometimes|required' : 'required';

        return [
            'lesson_id' => [
                $required,
                'integer',
                'exists:lessons,id',
            ],
            'type' => [
                $required,
                'in:'.implode(',', QuestionTypeEnum::getAllValues()),
            ],
            'difficulty_level' => [
                $required,
                'in:'.implode(',', QuestionDifficultyEnum::getAllValues()),
            ],
            'question_text' => [
                $required,
                'string',
            ],
            'audio_url' => [
                'sometimes',
                'nullable',
                'string',
                'max:2048',
            ],
            'order' => [
                $required,
                'integer',
                'min:0',
            ],
        ];
    }
}
