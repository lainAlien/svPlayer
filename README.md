# this has been moved
[>>>git gay](https://git.gay/lainalien/svPlayer)

this repo will no loinger receive updates

# svPlayer
a sunvox player for ur website

a small and basic sunvox player to embed in your website. it plays back a random or selected sunvox project, with the intent of being used for generative and noisy patches.

A demo of this repo can be found at [this link](https://lainalien.space/sunvox/svPlayer/). if it's not working there im working on it/depressed/please submit a pr lol

### features
- NEW!! *two* buttons
- a volume slider
- a song picker
- song info

### usage
+ place the svPlayer folder wherever in your website's directory structure you want
+ put your .sunvox projects in the `svPlayer/projects` folder
+ edit player.css if you want
+ load `svPlayer/player.php` into an `<embed>` tag wherever u want
  + you can use `?p=<project.sunvox>` to load the file `projects/<project.sunvox>`
  + `?v` gives more verbose song info output
  + `?f` displays a song picker, which lists songs in the `projects/` directory. it assumes ur replacing-spaces-with-hypheens in filenames and removes them; check out the `str_replace` command on line 14 to change this
  + `?d` hides download links in the file picker

### todo
* make it handle there being an empty project directory instead of jsut not drawing writing anything (some example projects have been added so u can quickly make sure shit works)
* make it stop LFO-driven projects
* make it more pretty

#### creds
powered by and based on the [sunvox library](https://warmplace.ru/soft/sunvox/sunvox_lib.php) if u didnt catch on
special thanks to sk0 in the discord for being patient lmao
