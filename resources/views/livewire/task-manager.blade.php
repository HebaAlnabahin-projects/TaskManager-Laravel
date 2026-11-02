
<div class="page-container">
    <div class="dashboard">

        <header class="page-header">
            <div class="brand">
                <div class="brand-icon">✓</div>
                <div>
                    <h1>Task Manager</h1>
                    <p>Organize your day, one task at a time.</p>
                </div>
            </div>

            <button type="button" wire:click="openAddTaskModal" class="primary-button">
                <span>＋</span> Add New Task
            </button>
        </header>

        <section class="welcome-section">
            <div>
                <h2>My Tasks</h2>
                <p>Keep track of what you need to get done.</p>
            </div>
            <div class="today-label">✦ Stay productive</div>
        </section>

        <section class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total-icon">▤</div>
                <div>
                    <p>Total Tasks</p>
                    <h3>{{ $tasks->count() }}</h3>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon pending-icon">◷</div>
                <div>
                    <p>Pending</p>
                    <h3>{{ $tasks->where('status', 'Pending')->count() }}</h3>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon completed-icon">✓</div>
                <div>
                    <p>Completed</p>
                    <h3>{{ $tasks->where('status', 'Completed')->count() }}</h3>
                </div>
            </div>
        </section>

        <section class="tasks-card">
            <div class="tasks-heading">
                <div>
                    <h2>All Tasks</h2>
                    <p>Your tasks are listed below.</p>
                </div>
                <span class="task-count">{{ $tasks->count() }} tasks</span>
            </div>

            @forelse ($tasks as $task)
                <div class="task-row" wire:key="task-{{ $task->id }}">

                    <button
                        type="button"
                        wire:click="toggleStatus({{ $task->id }})"
                        class="task-check {{ $task->status === 'Completed' ? 'checked' : '' }}"
                        title="Change task status"
                    >
                        @if ($task->status === 'Completed')
                            ✓
                        @endif
                    </button>

                    <div class="task-content">
                        <h3 class="{{ $task->status === 'Completed' ? 'task-done' : '' }}">
                            {{ $task->title }}
                        </h3>

                        <div class="task-meta">
                            <span class="status-badge {{ $task->status === 'Completed' ? 'completed-badge' : 'pending-badge' }}">
                                ● {{ $task->status }}
                            </span>

                            <span class="task-date">
                                ◷ Created: {{ $task->created_at->format('M d, Y - h:i A') }}
                            </span>

                            @if ($task->status === 'Completed' && $task->completed_at)
                                <span class="task-date completed-time">
                                    ✓ Completed: {{ $task->completed_at->format('M d, Y - h:i A') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="task-actions">
                        <button
                            type="button"
                            wire:click="editTask({{ $task->id }})"
                            class="icon-button edit-button"
                            title="Edit task"
                        >
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 20h9"/>
                                <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L8 18l-4 1 1-4Z"/>
                            </svg>
                        </button>

                        <button
                            type="button"
                            wire:click="openDeleteModal({{ $task->id }})"
                            class="icon-button delete-button"
                            title="Delete task"
                        >
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M3 6h18"/>
                                <path d="M8 6V4h8v2"/>
                                <path d="m19 6-1 14H6L5 6"/>
                                <path d="M10 11v5M14 11v5"/>
                            </svg>
                        </button>
                    </div>
                </div>

            @empty
                <div class="empty-state">
                    <div class="empty-icon">✦</div>
                    <h3>No tasks yet</h3>
                    <p>You haven't added any tasks. Start by creating your first one!</p>

                    <button type="button" wire:click="openAddTaskModal" class="primary-button">
                        ＋ Create Your First Task
                    </button>
                </div>
            @endforelse
        </section>

    </div>

    {{-- Add Task Modal --}}
    @if ($showAddTaskModal)
        <div class="modal-overlay" wire:click.self="closeAddTaskModal">
            <div class="modal-content" wire:key="add-task-modal">

                <button type="button" wire:click="closeAddTaskModal" class="close-modal">
                    &times;
                </button>

                <div class="modal-icon add-modal-icon">＋</div>

                <h2>Add New Task</h2>
                <p class="modal-description">What would you like to accomplish?</p>

                <form wire:submit="addTask">
                    <label for="new-task-title">Task title</label>

                    <input
                        id="new-task-title"
                        type="text"
                        wire:model="title"
                        placeholder="Enter your task..."
                        autofocus
                    >

                    @error('title')
                        <p class="error-message">{{ $message }}</p>
                    @enderror

                    <div class="modal-actions">
                        <button
                            type="button"
                            wire:click="closeAddTaskModal"
                            class="secondary-button"
                        >
                            Cancel
                        </button>

                        <button type="submit" class="primary-button">
                            ＋ Add Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Edit Task Modal --}}
    @if ($editingTaskId)
        <div class="modal-overlay" wire:click.self="cancelEdit">
            <div class="modal-content" wire:key="edit-task-modal">

                <button type="button" wire:click="cancelEdit" class="close-modal">
                    &times;
                </button>

                <div class="modal-icon edit-modal-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 20h9"/>
                        <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L8 18l-4 1 1-4Z"/>
                    </svg>
                </div>

                <h2>Edit Task</h2>
                <p class="modal-description">Make changes to your task.</p>

                <form wire:submit="updateTask">
                    <label for="edit-task-title">Task title</label>

                    <input
                        id="edit-task-title"
                        type="text"
                        wire:model="editTitle"
                        placeholder="Enter your task..."
                        autofocus
                    >

                    @error('editTitle')
                        <p class="error-message">{{ $message }}</p>
                    @enderror

                    <div class="modal-actions">
                        <button
                            type="button"
                            wire:click="cancelEdit"
                            class="secondary-button"
                        >
                            Cancel
                        </button>

                        <button type="submit" class="primary-button">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if ($showDeleteModal)
        <div class="modal-overlay" wire:click.self="closeDeleteModal">
            <div class="modal-content delete-modal" wire:key="delete-task-modal">

                <button
                    type="button"
                    wire:click="closeDeleteModal"
                    class="close-modal"
                >
                    &times;
                </button>

                <h2>Delete Task?</h2>

                <p class="delete-warning">
                    Are you sure you want to delete this task?
                </p>

                <div class="modal-actions">

                    <button
                        type="button"
                        wire:click="closeDeleteModal"
                        class="secondary-button"
                    >
                        Cancel
                    </button>

                    <button
                        type="button"
                        wire:click="confirmDelete"
                        class="confirm-delete-button"
                    >
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M3 6h18"/>
                            <path d="M8 6V4h8v2"/>
                            <path d="M19 6l-1 14H6L5 6"/>
                            <path d="M10 11v5M14 11v5"/>
                        </svg>
                        Delete
                    </button>

                </div>
            </div>
        </div>
    @endif
</div>

<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        background: #f6f5fc;
        color: #292943;
        font-family: Arial, Helvetica, sans-serif;
    }

    button, input {
        font: inherit;
    }

    button {
        cursor: pointer;
    }

    .page-container {
        min-height: 100vh;
        padding: 48px 20px;
        background: linear-gradient(135deg, #f7f5ff 0%, #f0f4ff 100%);
    }

    .dashboard {
        max-width: 1050px;
        margin: 0 auto;
    }

    .page-header, .brand, .welcome-section, .tasks-heading,
    .task-row, .task-meta, .task-actions, .modal-actions {
        display: flex;
        align-items: center;
    }

    .page-header, .welcome-section, .tasks-heading {
        justify-content: space-between;
    }

    .brand {
        gap: 14px;
    }

    .brand-icon {
        width: 48px;
        height: 48px;
        display: grid;
        place-items: center;
        border-radius: 15px;
        color: white;
        font-size: 25px;
        font-weight: bold;
        background: linear-gradient(135deg, #8271e8, #69a0ec);
        box-shadow: 0 7px 18px #8174df40;
    }

    .brand h1 {
        margin: 0 0 5px;
        font-size: 25px;
    }

    .brand p, .welcome-section p, .tasks-heading p {
        margin: 0;
        color: #89899e;
        font-size: 13px;
    }

    .primary-button {
        border: 0;
        border-radius: 11px;
        padding: 12px 18px;
        color: white;
        background: linear-gradient(110deg, #8170df, #6999e9);
        font-size: 13px;
        font-weight: 700;
        box-shadow: 0 6px 15px #8170df30;
        transition: .2s ease;
    }

    .primary-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 9px 20px #8170df40;
    }

    .primary-button span {
        font-size: 18px;
        vertical-align: -1px;
    }

    .welcome-section {
        margin: 48px 0 22px;
    }

    .welcome-section h2, .tasks-heading h2 {
        margin: 0 0 7px;
        font-size: 21px;
    }

    .today-label {
        padding: 9px 14px;
        border-radius: 20px;
        background: #eeebff;
        color: #7969cf;
        font-size: 12px;
        font-weight: 700;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 17px;
        margin-bottom: 25px;
    }

    .stat-card {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 21px;
        border: 1px solid #eeedf7;
        border-radius: 16px;
        background: white;
        box-shadow: 0 5px 18px #34305b08;
    }

    .stat-icon {
        width: 43px;
        height: 43px;
        display: grid;
        place-items: center;
        border-radius: 13px;
        font-size: 21px;
        font-weight: bold;
    }

    .total-icon { background: #eeebff; color: #7968d5; }
    .pending-icon { background: #fff4df; color: #d99a36; }
    .completed-icon { background: #e3f8ef; color: #26a879; }

    .stat-card p {
        margin: 0 0 5px;
        color: #9291a5;
        font-size: 12px;
    }

    .stat-card h3 {
        margin: 0;
        font-size: 23px;
    }

    .tasks-card {
        padding: 25px;
        border: 1px solid #eeedf7;
        border-radius: 18px;
        background: white;
        box-shadow: 0 7px 25px #34305b09;
    }

    .tasks-heading {
        padding-bottom: 20px;
        border-bottom: 1px solid #f0eff6;
    }

    .tasks-heading h2 {
        font-size: 18px;
    }

    .task-count {
        padding: 7px 12px;
        border-radius: 20px;
        background: #f2f0ff;
        color: #786bd0;
        font-size: 11px;
        font-weight: 700;
    }

    .task-row {
        gap: 15px;
        padding: 20px 4px;
        border-bottom: 1px solid #f1f0f6;
    }

    .task-row:last-child {
        border-bottom: 0;
    }

    .task-check {
        flex: 0 0 23px;
        width: 23px;
        height: 23px;
        padding: 0;
        border: 2px solid #c9c5e8;
        border-radius: 50%;
        background: white;
        color: white;
        font-size: 13px;
        font-weight: bold;
        display: grid;
        place-items: center;
        transition: .2s;
    }

    .task-check:hover {
        border-color: #8271df;
    }

    .task-check.checked {
        border-color: #35b58a;
        background: #35b58a;
    }

    .task-content {
        flex: 1;
        min-width: 0;
    }

    .task-content h3 {
        margin: 0 0 10px;
        color: #35344e;
        font-size: 14px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .task-content h3.task-done {
        color: #9998a8;
        text-decoration: line-through;
    }

    .task-meta {
        flex-wrap: wrap;
        gap: 9px 14px;
    }

    .status-badge {
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
    }

    .pending-badge {
        color: #bd852c;
        background: #fff5e5;
    }

    .completed-badge {
        color: #239b70;
        background: #e7f8f0;
    }

    .task-date {
        color: #9998a9;
        font-size: 11px;
    }

    .completed-time {
        color: #15966b;
        font-weight: 600;
    }

    .task-actions {
        gap: 8px;
    }

    .icon-button {
        width: 35px;
        height: 35px;
        display: grid;
        place-items: center;
        border: 0;
        border-radius: 10px;
        background: transparent;
        transition: .2s;
    }

    .icon-button svg, .modal-icon svg {
        width: 18px;
        height: 18px;
        fill: none;
        stroke: currentColor;
        stroke-width: 1.8;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .edit-button {
        color: #8271d9;
    }

    .edit-button:hover {
        background: #f0edff;
    }

    .delete-button {
        color: #e16b78;
    }

    .delete-button:hover {
        background: #fff0f1;
        color: #d83f50;
    }

    .empty-state {
        padding: 55px 15px 40px;
        text-align: center;
    }

    .empty-icon {
        width: 60px;
        height: 60px;
        display: grid;
        place-items: center;
        margin: 0 auto 17px;
        border-radius: 20px;
        color: #8271df;
        background: #f0edff;
        font-size: 28px;
    }

    .empty-state h3 {
        margin: 0 0 8px;
        font-size: 17px;
    }

    .empty-state p {
        margin: 0 auto 20px;
        color: #9291a5;
        font-size: 13px;
    }

    /* Modals */

    .modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: #24213b80;
        backdrop-filter: blur(4px);
    }

    .modal-content {
        position: relative;
        width: 100%;
        max-width: 440px;
        padding: 32px;
        border: 1px solid #eeedf7;
        border-radius: 20px;
        background: white;
        box-shadow: 0 20px 60px #17142d30;
        animation: modal-appear .2s ease-out;
    }

    @keyframes modal-appear {
        from {
            opacity: 0;
            transform: translateY(10px) scale(.98);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .close-modal {
        position: absolute;
        top: 15px;
        right: 17px;
        width: 30px;
        height: 30px;
        border: 0;
        border-radius: 8px;
        background: #f5f4fa;
        color: #85849a;
        font-size: 22px;
        line-height: 1;
    }

    .close-modal:hover {
        background: #eeecf8;
        color: #514b7b;
    }

    .modal-icon {
        width: 48px;
        height: 48px;
        display: grid;
        place-items: center;
        margin-bottom: 17px;
        border-radius: 15px;
        font-size: 27px;
        font-weight: 400;
    }

    .add-modal-icon {
        color: #7969d2;
        background: #efedff;
    }

    .edit-modal-icon {
        color: #648bdc;
        background: #eaf1ff;
    }

    .modal-content h2 {
        margin: 0 0 7px;
        font-size: 21px;
    }

    .modal-description {
        margin: 0 0 25px;
        color: #9291a5;
        font-size: 13px;
    }

    .modal-content label {
        display: block;
        margin-bottom: 9px;
        color: #55536c;
        font-size: 12px;
        font-weight: 700;
    }

    .modal-content input {
        width: 100%;
        padding: 13px 14px;
        border: 1px solid #e5e3ef;
        border-radius: 10px;
        outline: none;
        color: #34334b;
        background: #fcfbff;
        font-size: 13px;
        transition: .2s;
    }

    .modal-content input:focus {
        border-color: #9183e3;
        box-shadow: 0 0 0 3px #9183e320;
    }

    .error-message {
        margin: 7px 0 0;
        color: #d94f61;
        font-size: 12px;
    }

    .modal-actions {
        justify-content: flex-end;
        gap: 10px;
        margin-top: 25px;
    }

    .secondary-button {
        padding: 12px 17px;
        border: 1px solid #e7e5ef;
        border-radius: 10px;
        color: #77758b;
        background: white;
        font-size: 13px;
        font-weight: 700;
    }

    .secondary-button:hover {
        background: #f8f7fc;
    }

    /* Delete Modal */

    .delete-modal {
        max-width: 400px;
        padding: 35px 30px 28px;
    }

    .delete-modal h2 {
        margin: 0 0 12px;
        color: #dc5365;
        font-size: 21px;
    }

    .delete-warning {
        margin: 0;
        color: #77758b;
        font-size: 14px;
        line-height: 1.6;
    }

    .confirm-delete-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 18px;
        border: none;
        border-radius: 10px;
        color: white;
        background: #dc5365;
        font-size: 13px;
        font-weight: 700;
        transition: .2s;
    }

    .confirm-delete-button:hover {
        background: #c93d51;
    }

    .confirm-delete-button svg {
        display: block;
        width: 17px;
        height: 17px;
        flex-shrink: 0;
        fill: none;
        stroke: currentColor;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    /* Navigation Link - Same style as Go page */

    .navigation-link {
        text-align: center;
        margin-top: 5px;
    }

    .back-link {
        display: inline-block;
        color: #7564b5;
        text-decoration: none;
        font-size: 13px;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    @media (max-width: 650px) {
        .page-container {
            padding: 28px 13px;
        }

        .page-header {
            align-items: flex-start;
            gap: 15px;
        }

        .brand h1 {
            font-size: 20px;
        }

        .page-header > .primary-button {
            padding: 10px 12px;
            white-space: nowrap;
            font-size: 11px;
        }

        .welcome-section {
            margin-top: 35px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .stat-card {
            padding: 14px 17px;
        }

        .tasks-card {
            padding: 17px;
        }

        .task-row {
            align-items: flex-start;
            gap: 10px;
        }

        .task-check {
            margin-top: 1px;
        }

        .task-actions {
            gap: 2px;
        }

        .icon-button {
            width: 30px;
            height: 32px;
        }

        .task-meta {
            align-items: flex-start;
            flex-direction: column;
            gap: 7px;
        }

        .today-label {
            display: none;
        }

        .modal-content {
            padding: 27px 22px;
        }
    }
</style>
