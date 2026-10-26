# Heaven on Earth page: paste segments

Used when the full `content/blocks/heaven-on-earth.html` cannot be pasted in one go (the browser tool refuses the long numbers in the existing slideshow image addresses). In the page's Code editor keep the three existing blocks in place and replace only the text around them:

1. `part1.txt` before the first Jetpack slideshow block
2. `part2.txt` between the first and second slideshow blocks
3. `part3.txt` between the second slideshow block and the Jetpack contact form block
4. `part4.txt` after the contact form block

Everything else in the old content is removed. The result equals `heaven-on-earth.html` with each KEEP placeholder (a Custom HTML block holding only the KEEP comment) replaced by the existing Jetpack block.
