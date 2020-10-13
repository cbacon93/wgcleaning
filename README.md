# WG Cleaning Simulator
## Summary
This Simulator distributes WG cleaning tasks to all roommates equally and fair. It keeps a dirt-score of each task. At the end of each day the dirt-score increases by a predefined amount. The most dirty things get assigned to roommates every week. The dirt score gets reset when the task is done. If a task is not done it will be assigned the next week again.

## Installation
1. Clone the repository
2. Import the wg_cleaning.sql file.  
3. Edit the include.php file
4. Setup crontab to run dirty_rooms.php every day
5. Setup crontab to run build_schedule.php at the end of the week (sunday night)
6. Done.

## Usage
Use clean_rooms.php [WEEK NR] to mark the rooms cleaned. The entry in roster gets marked done, the dirt score in the duties table gets reset to zero and a new entry will be created in the history table.


