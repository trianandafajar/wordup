<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RenameUnitImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wordup:rename-unit-images {unit} {--all-five : Force 5 questions per lesson for all 15 lessons}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rename global numbered images to unit-specific format (main.png, q-n.png)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $unit = $this->argument('unit');
        $allFive = $this->option('all-five');
        $globalIndex = 1;

        $this->info("Starting rename for Unit {$unit}...");

        for ($lesson = 1; $lesson <= 15; $lesson++) {
            $qCount = ($allFive) ? 5 : (in_array($lesson, [5, 10, 15]) ? 10 : 5);
            $path = public_path("images/units/unit-{$unit}/lesson-{$lesson}");

            if (! File::isDirectory($path)) {
                $this->warn("Directory not found: {$path}");
                // We still increment globalIndex because the numbering is global
                $globalIndex += (1 + $qCount);
                continue;
            }

            $this->comment("Processing Lesson {$lesson}...");

            // Rename main
            $this->renameFile($path, $globalIndex++, 'main.png');

            // Rename questions
            for ($q = 1; $q <= $qCount; $q++) {
                $this->renameFile($path, $globalIndex++, "q-{$q}.png");
            }
        }

        $this->info("Done.");
    }

    private function renameFile(string $path, int $oldIndex, string $newName): void
    {
        $oldFile = "{$path}/{$oldIndex}.png";
        $newFile = "{$path}/{$newName}";

        if (File::exists($oldFile)) {
            File::move($oldFile, $newFile);
            $this->line("  Renamed: {$oldIndex}.png -> {$newName}");
        } else {
            // Check if already renamed (for idempotency)
            if (File::exists($newFile)) {
                $this->line("  Skipping (already exists): {$newName}");
            } else {
                $this->error("  File not found: {$oldIndex}.png");
            }
        }
    }
}
