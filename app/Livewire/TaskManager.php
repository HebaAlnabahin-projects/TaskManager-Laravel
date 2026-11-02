<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;

class TaskManager extends Component
{
    public $title = '';
    public $editTitle = '';
    public $editingTaskId = null;
    public $showAddTaskModal = false;
    public $deletingTaskId = null;
    public $showDeleteModal = false;

    public function openAddTaskModal()
    {
        $this->reset('title');
        $this->resetValidation();
        $this->showAddTaskModal = true;
    }

    public function closeAddTaskModal()
    {
        $this->reset('title');
        $this->resetValidation();
        $this->showAddTaskModal = false;
    }

    public function addTask()
    {
        $this->validate([
            'title' => 'required|string|max:255',
        ]);

        Task::create([
            'title' => $this->title,
            'status' => 'Pending',
        ]);

        $this->reset('title');
        $this->resetValidation();
        $this->showAddTaskModal = false;
    }

    public function editTask($id)
    {
        $task = Task::findOrFail($id);

        $this->editingTaskId = $task->id;
        $this->editTitle = $task->title;

        $this->resetValidation();
    }

    public function updateTask()
    {
        $this->validate([
            'editTitle' => 'required|string|max:255',
        ]);

        $task = Task::findOrFail($this->editingTaskId);

        $task->update([
            'title' => $this->editTitle,
        ]);

        $this->reset('editTitle', 'editingTaskId');
        $this->resetValidation();
    }

    public function cancelEdit()
    {
        $this->reset('editTitle', 'editingTaskId');
        $this->resetValidation();
    }

    public function openDeleteModal($id)
    {
        $this->deletingTaskId = $id;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->reset('deletingTaskId');
        $this->showDeleteModal = false;
    }

    public function confirmDelete()
    {
        $task = Task::findOrFail($this->deletingTaskId);

        $task->delete();

        $this->reset('deletingTaskId');
        $this->showDeleteModal = false;
    }

    public function toggleStatus($id)
    {
        $task = Task::findOrFail($id);

        if ($task->status === 'Pending') {
            $task->update([
                'status' => 'Completed',
                'completed_at' => now(),
            ]);
        } else {
            $task->update([
                'status' => 'Pending',
                'completed_at' => null,
            ]);
        }
    }

    public function render()
    {
        $tasks = Task::latest()->get();

        return view('livewire.task-manager', [
            'tasks' => $tasks,
        ]);
    }
}