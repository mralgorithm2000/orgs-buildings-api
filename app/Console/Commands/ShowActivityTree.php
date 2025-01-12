<?php

namespace App\Console\Commands;

use App\Models\Activity;
use Illuminate\Console\Command;

class ShowActivityTree extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activity:tree {parentId=0} {--level=5}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display the activity tree starting from a given parent activity ID.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $parentId = $this->argument('parentId');
        $level = $this->option('level');

        if ($parentId === 'null') {
            $parentId = null;
        }

        $this->info("Displaying activity tree for parent ID: $parentId, up to level: $level");

        $this->displayTree($parentId, $level);
    }

     /**
     * Recursively display the tree of activities.
     */
    protected function displayTree($parentId, $level, $indent = '')
    {
        // Retrieve the activities that belong to the given parent
        $activities = Activity::where('parent_id', $parentId)->get();

        // If there are no activities, return
        if ($activities->isEmpty()) {
            return;
        }

        // Loop through the activities and display them
        foreach ($activities as $activity) {
            $this->line($indent . " " . $activity->id . " " . $activity->name); // Assuming 'name' is the field you want to display

            // Recursively display children if the level is greater than 0
            if ($level > 0) {
                $this->displayTree($activity->id, $level - 1, $indent . '--');
            }
        }
    }
}
