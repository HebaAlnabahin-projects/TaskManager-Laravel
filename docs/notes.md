# Assignment 2 - Notes

## 1. Biggest Docker Problem and How I Solved It

The biggest problem I faced in this assignment was the 419 Page Expired error when I tried to add a new task using Livewire.

At first, the application was running normally, but whenever I tried to add a task, the request failed. I spent a lot of time trying to find the reason. I checked the Docker containers, Laravel session settings, and logs, but the problem was still there.

After doing more testing, I discovered that there was an extra empty line before the `<?php` tag in the `routes/web.php` file. This caused PHP to send output before Laravel could send the HTTP headers and cookies.

I removed that extra line, rebuilt the Docker image, and tested the application again. After that, the cookies started working correctly, and I was able to add tasks successfully.

This experience taught me that debugging is not always about changing Docker settings. Sometimes, the problem comes from a small detail in the application code, so it is important to investigate carefully and test different possibilities.

## 2. Git and GitHub Experience

Another important part of this assignment was learning how to use Git and GitHub to manage and share my project.

I started by initializing a Git repository inside my Laravel project and creating the main branch. Then, I added a `.gitignore` file to make sure that unnecessary files, such as the `.env` file, `vendor`, and `node_modules`, were not uploaded to GitHub.

One important thing I learned was the difference between Git and GitHub. Git helps me track changes and create commits locally, while GitHub allows me to store and share my repository online.

I organized my work into separate commits. The first commit contained the Laravel application, while the second included the Docker configuration and documentation. After that, I created a GitHub repository, connected it to my local project using a remote, and pushed my commits to the main branch.

I also learned how to check the repository status, review commit history, and understand the difference between staging, committing, and pushing changes.

This assignment helped me understand how Git and GitHub are used in real development workflows and why version control is important when working on software projects.