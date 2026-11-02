# Assignment 2 - Notes

## 1. Biggest Docker Problem and How I Solved It

The biggest problem I faced in this assignment was the 419 Page Expired error when I tried to add a new task using Livewire.

At first, the application was running normally, but whenever I tried to add a task, the request failed. I spent a lot of time trying to find the reason. I checked the Docker containers, Laravel session settings, and logs, but the problem was still there.

After doing more testing, I discovered that there was an extra empty line before the `<?php` tag in the `routes/web.php` file. This caused PHP to send output before Laravel could send the HTTP headers and cookies.

I removed that extra line, rebuilt the Docker image, and tested the application again. After that, the cookies started working correctly, and I was able to add tasks successfully.

This experience taught me that debugging is not always about changing Docker settings. Sometimes, the problem comes from a small detail in the application code, so it is important to investigate carefully and test different possibilities.
