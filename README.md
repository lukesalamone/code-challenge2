# Code Challenge 2
Use PHP to complete the following tasks.

Manipulate the data in the tab delimited file: `test_data_in.txt`  

1. Convert the ID to the match the following example patterns:
  * 111001111 converts to xxx-xx-1111.
  * 321009123 converts to xxx-xx-9123.
2. Use the preg_replace function to remove any characters that are not letters, hyphens, or single spaces from the FIRST and LAST names.
3. Add another piece of data after EFFECTIVE called TERM. Calculate this TERM to be 100 days after the EFFECTIVE. Be sure to check if the TERM falls on a Saturday or Sunday. If it does, move the TERM to be the first Monday after that Saturday/Sunday.

Write the above modifications to a new tab delimited file: `test_data_out.txt`

1. Make sure the header is included at the top
2. Sort the output in the following order:
  1. Type M records first
  2. Type D records second
  3. Type L records third

Return both your code and the output file.
