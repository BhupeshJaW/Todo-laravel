Build a basic todo app
======================

Start by downloading and installing your favorite editor/IDE.

Clone the repository and set it up according to instructions in the readme.

The project contains some basic models, test data and an empty homepage.

Your task is to implement a basic todo app based on a provided HTML template. The template is based on [Basecoat UI](https://basecoatui.com/), a plain HTML component library based on Shadcn.

You can find the design template in `design.html` in the root of this project.

The project uses sqlite for a database by default. This is fine, but if you want you can use another database engine. The DBNgin app can be used to easily spin up databases.

Workflow instructions
---------------------

1. Create a `feature/task-list` branch for your work
2. Commit your work in logical steps (using [conventional commits](https://www.conventionalcommits.org/en/v1.0.0/) is a pre)
3. Open a merge request to main when done

Requirements
------------

Requirements are listed in order of importance. Preferably you should stick to this order. If you feel like you are stuck on one requirement but think a next requirement is trivial to implement, feel free to continue with the next requirement.

Requirements 2 to 5 can be finished without using JavaScript. Bonus points if you manage to do this!

### 1) Setup

- Install Basecoat UI into the project

### 2) Listing tasks

- Implement task list according to design
- Tasks should be ordered by urgency first and status second. This means tasks with a due date should appear on top, ordered by oldest due date first, next tasks without due date ordered by latest created_at and then tasks that are done.

### 3) Adding tasks

- Implement add task form according to design
- The form should do a request to POST /tasks and return to / on success
- The form should be validated: description is required, due date is optional and should be a valid date not in the past
- The validation should happen on the backend and errors should be displayed below the relevant field
- BONUS: highlight the added tasks with a fade out effect

### 4) Finishing tasks

- It should be possible to mark a task as done
- Implement the button in such a way that it sends a request to PATCH /tasks/{id} where the task is marked as done. Afterwards the user should return to /

### 5) Filtering tasks

- A user should be able to filter task based on overdue or open status
- Implement this is such a way that a `?status=` query parameter is added to the main route
- `status` can be empty (all), overdue (Overdue) or open (All tasks with done=false)
- The filter tasks field should reflect the current state of the filter
- When checking of tasks the filter should maintain state, so when clicking on "Done" for a tasks while the filter is "Overdue", the filter should stay on overdue afterwards

### 6) BONUS: in place editing of tasks

- A user should be able to edit a task description or due date in place
- When clicking on a description or due date, the task should be replaced by a form to edit both fields
- After submitting the list should update to the correct state (when due date is changed)
