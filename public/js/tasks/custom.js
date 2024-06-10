document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM fully loaded and parsed');

    const deleteButtons = document.querySelectorAll('.delete-button');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const taskId = this.getAttribute('data-id');
            console.log('Delete button clicked. Task ID:', taskId);
            document.getElementById('taskId').value = taskId;
            $('#deleteModal').data('taskId', taskId).modal('show');
        });
    });

    $('#deleteModal').on('show.bs.modal', function (event) {
        const taskId = $(this).data('taskId');
        console.log('Modal opened with Task ID:', taskId);
        if (!taskId) {
            console.error('No task ID found.');
            return;
        }
        const modal = $(this);
        modal.find('#taskId').val(taskId);
        modal.find('#deleteForm').attr('action', '/task/delete/' + taskId);
    });
});
